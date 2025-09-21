<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $services = Service::where('is_active', 1)->get();
            return successResponse("Services fetched successfully.", $services);
        } catch (ValidationException $e) {
            return errorResponse((string) $e->errors());
        }
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
            ]);
            $service = new Service([
                'name' => $request->input('name'),
            ]);
            $service->save();
            return successResponse('Service created successfully', $service);
        } catch (ValidationException $e) {
            return errorResponse("Failed to store service");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        return successResponse("Service fetched successfully.", $service);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        try {
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'is_active' => 'sometimes|in:Y,N',
            ]);
            $service->update($request->only(['name', 'is_active']));
            return successResponse($service, 'Service updated successfully.');
        } catch (ValidationException $e) {
            return errorResponse("Failed to update service.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        try {
            $service->delete();
            return successResponse("Service deleted successfully");
        } catch (ValidationException $e) {
            return errorResponse("Failed to delete service.");
        }
    }
}
