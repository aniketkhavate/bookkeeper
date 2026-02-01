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
        $user = auth()->user(); // get logged-in user

        // if ($user->isAdmin()) {
        if (true) {
            // Admin sees all customers
            $customers = Customer::with('creator:id,name')->where('is_active', 1)->orderBy('id', 'desc')->get();
        } else if ($user->isEmployee()) {
            // Employee sees only the customers they created
            $customers = Customer::with('creator:id,name')->where('is_active', 1)
                ->where('created_by', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            return errorResponse('Access denied.', [], 403);
        }

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
                'phone' => 'required|string|max:15|unique:customers,phone',
                'address' => 'nullable|string',
                'branch' => 'nullable|string|max:255',
            ]);
            $request->merge(['created_by' => auth()->id()]);
            $customer = Customer::create($request->all());
            return successResponse('Customer created successfully.', $customer);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->first()[0];
            return errorResponse($firstError, $e->errors());
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
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
            $firstError = collect($e->errors())->first()[0];
            return errorResponse($firstError, $e->errors());
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
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
            return errorResponse($e->getMessage());
        }
    }
}
