<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::where('is_active', 1)->get();
        return successResponse('Customer List.', $customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'phone' => 'required|string|max:15',
                'address' => 'nullable|string',
                'branch' => 'nullable|string|max:255',
            ]);
            $request->merge(['created_by' => auth()->id()]);
            $customer = Customer::create($request->all());
            return successResponse('Customer created successfully.', $customer);
        } catch (ValidationException $e) {
            return errorResponse("Failed to save customer details.");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        try {
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:customers,email,' . $customer->id,
                'phone' => 'sometimes|string|max:15',
                'address' => 'nullable|string',
                'branch' => 'nullable|string|max:255',
            ]);
            $request->merge(['created_by' => auth()->id()]);
            $customer->update($request->all());
            return successResponse('Customer updated successfully.', $customer);
        } catch (ValidationException $e) {
            return errorResponse("Failed to update customer details.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return successResponse('Customer deleted successfully.');
        } catch (\Exception $e) {
            return errorResponse("Failed to delete customer details.");
        }
    }
}
