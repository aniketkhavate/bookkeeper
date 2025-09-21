<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceEntry;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServiceEntryController extends Controller
{
    public function index()
    {
        $serviceEntries = ServiceEntry::with(['customer', 'service'])->get();
        return successResponse("Service entries fetched successfully.", $serviceEntries);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'service_id' => 'required|exists:services,id',
                'rate' => 'required|numeric',
                'quantity' => 'required|integer',
                'total_bill' => 'required',
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
            return errorResponse("Failed to save service entry.");
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'rate' => 'required|numeric',
                'quantity' => 'required|integer',
            ]);
            $serviceEntry = ServiceEntry::findOrFail($id);
            $totalBill = $request->rate * $request->quantity;
            $serviceEntry->update([
                'rate' => $request->rate,
                'quantity' => $request->quantity,
                'total_bill' => $totalBill,
            ]);
            return successResponse('Service entry updated successfully.', $serviceEntry);
        } catch (ValidationException $e) {
            return errorResponse("Failed to update service entry.");
        }
    }

    public function destroy($id)
    {
        try {
            $serviceEntry = ServiceEntry::findOrFail($id);
            $serviceEntry->delete();
            return successResponse("Service entry deleted successfully");
        } catch (\Exception $e) {
            return errorResponse("Failed to delete service entry.");
        }
    }

    public function getRawData()
    {
        try {
            $services = Service::where('is_active', 1)->get();
            $customers = Customer::where('is_active', 1)->get();
            $data = [
                'customers' => $customers,
                'services' => $services,
            ];
            return successResponse("Customer & Service List.", $data);
        } catch (\Exception $e) {
            return errorResponse("fFailed to fetch details.");
        }
    }
}
