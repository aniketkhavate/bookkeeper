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
        return response()->json([
            'status' => true,
            'data' => $serviceEntries
        ], 200);
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
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Service entry created successfully',
                    'data' => $serviceEntry
                ],
                200
            );
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->errors()
            ], 200);
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
            return response()->json([
                'status' => true,
                'message' => 'Service entry updated successfully',
                'data' => $serviceEntry
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->errors()
            ], 200);
        }
    }

    public function destroy($id)
    {
        try {
            $serviceEntry = ServiceEntry::findOrFail($id);
            $serviceEntry->delete();
            return response()->json([
                'status' => true,
                'message' => 'Service entry deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
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
            return response()->json([
                'status' => true,
                'message' => 'Customer & Service List',
                'data'  => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
