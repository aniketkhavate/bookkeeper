<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceEntry;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServiceEntryController extends Controller
{
    private $serviceEntryModel;

    public function __construct()
    {
        $this->serviceEntryModel = new ServiceEntry();
    }

    public function index(Request $request)
    {
        $request->validate([
            'id' => 'nullable',
            'customer_id' => 'nullable|integer|min:0'
        ]);
        $where = [];
        if ($request->filled('id')) {
            $where['id'] = $request->id;
        }
        if ($request->filled('customer_id') && $request->customer_id != 0) {
            $where['customer_id'] = $request->customer_id;
        }
        $serviceEntries = $this->serviceEntryModel->getServiceEntries($where);
        return successResponse("Service entries fetched successfully.", $serviceEntries);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'service_id' => 'required|exists:services,id',
                'rate' => 'required|numeric',
                'quantity' => 'required|integer'
            ]);
            $totalBill = $request->rate * $request->quantity;
            $serviceEntry = ServiceEntry::create([
                'customer_id' => $request->customer_id,
                'service_id' => $request->service_id,
                'rate' => $request->rate,
                'quantity' => $request->quantity,
                'total_bill' => $totalBill,
            ]);
            $serviceEntry->load(['customer', 'service']);
            return successResponse('Service entry created successfully.', $serviceEntry);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->first()[0];
            return errorResponse($firstError, $e->errors());
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'service_id' => 'required|exists:services,id',
                'rate' => 'required|numeric',
                'quantity' => 'required|integer',
                'status' => 'required|in:pending,in-progress,completed'
            ]);
            $serviceEntry = ServiceEntry::findOrFail($id);
            $totalBill = $request->rate * $request->quantity;
            $serviceEntry->update(
                [
                    'customer_id' => $request->customer_id,
                    'service_id' => $request->service_id,
                    'rate' => $request->rate,
                    'quantity' => $request->quantity,
                    'total_bill' => $totalBill,
                    'status' => $request->status
                ]
            );
            return successResponse('Service entry updated successfully.', $serviceEntry);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->first()[0];
            return errorResponse($firstError, $e->errors());
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $serviceEntry = ServiceEntry::findOrFail($id);
            $serviceEntry->delete();
            return successResponse("Service entry deleted successfully");
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getRawData()
    {
        try {
            $services = Service::where('is_active', 1)->get();
            $customers = Customer::where('is_active', 1)->get();
            $serviceEntries = ServiceEntry::with(['customer', 'service'])->get();
            $data = [
                'customers' => $customers,
                'services' => $services,
                'serviceEntries' => $serviceEntries
            ];
            return successResponse("Customer & Service List.", $data);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }
}
