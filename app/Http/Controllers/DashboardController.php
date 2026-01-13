<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = [
                // core
                'total_customers' => Customer::where('is_active', 1)->count(),
                'total_services'  => Service::where('is_active', 1)->count(),
                'total_entries'   => ServiceEntry::count(),

                // status
                'entries_by_status' => [
                    'pending'   => ServiceEntry::where('status', 'pending')->count(),
                    'completed' => ServiceEntry::where('status', 'completed')->count(),
                    'cancelled' => ServiceEntry::where('status', 'cancelled')->count(),
                ],

                // time based
                'today_entries' => ServiceEntry::whereDate('created_at', Carbon::today())->count(),
                'this_month_entries' => ServiceEntry::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count(),

                // revenue
                'total_revenue' => ServiceEntry::where('status', 'completed')->sum('total_bill'),
                'today_revenue' => ServiceEntry::where('status', 'completed')
                    ->whereDate('created_at', Carbon::today())
                    ->sum('total_bill'),

                // customers
                'inactive_customers' => Customer::where('is_active', 0)->count(),

                // alerts
                'pending_over_7_days' => ServiceEntry::where('status', 'pending')
                    ->whereDate('created_at', '<=', Carbon::now()->subDays(7))
                    ->count(),
            ];

            return successResponse("Dashboard reports fetched successfully.", $data);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }
}
