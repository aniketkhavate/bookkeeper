<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceEntry;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = [
                // basic counts
                'total_customers' => Customer::where('is_active', 1)->count(),
                'total_services'  => Service::where('is_active', 1)->count(),
                'total_entries'   => ServiceEntry::count(),

                // status-wise counts
                'pending_entries' => ServiceEntry::where('status', 'pending')->count(),
                'completed_entries'  => ServiceEntry::where('status', 'completed')->count(),
            ];

            return successResponse("Dashboard reports fetched successfully.", $data);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }
}
