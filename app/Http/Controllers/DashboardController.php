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
            $user = auth()->user();

            // if ($user->isAdmin()) {
            if (true) {
                // Admin sees everything
                $total_customers = Customer::where('is_active', 1)->count();
                $total_services  = Service::where('is_active', 1)->count();
                $total_entries   = ServiceEntry::count();

                $entries_by_status = [
                    'pending'   => ServiceEntry::where('status', 'pending')->count(),
                    'completed' => ServiceEntry::where('status', 'completed')->count(),
                    'cancelled' => ServiceEntry::where('status', 'cancelled')->count(),
                ];

                $today_entries = ServiceEntry::whereDate('created_at', Carbon::today())->count();
                $this_month_entries = ServiceEntry::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count();

                $total_revenue = ServiceEntry::where('status', 'completed')->sum('total_bill');
                $today_revenue = ServiceEntry::where('status', 'completed')
                    ->whereDate('created_at', Carbon::today())
                    ->sum('total_bill');

                $inactive_customers = Customer::where('is_active', 0)->count();

                $pending_over_7_days = ServiceEntry::where('status', 'pending')
                    ->whereDate('created_at', '<=', Carbon::now()->subDays(7))
                    ->count();
            } else if ($user->isEmployee()) {
                // Employee sees only their customers & entries
                $total_customers = Customer::where('is_active', 1)
                    ->where('created_by', $user->id)
                    ->count();

                $total_services  = Service::where('is_active', 1)->count(); // can see all services

                $total_entries   = ServiceEntry::whereHas('customer', function ($q) use ($user) {
                    $q->where('created_by', $user->id);
                })->count();

                $entries_by_status = [
                    'pending'   => ServiceEntry::whereHas('customer', function ($q) use ($user) {
                        $q->where('created_by', $user->id);
                    })->where('status', 'pending')->count(),
                    'completed' => ServiceEntry::whereHas('customer', function ($q) use ($user) {
                        $q->where('created_by', $user->id);
                    })->where('status', 'completed')->count(),
                    'cancelled' => ServiceEntry::whereHas('customer', function ($q) use ($user) {
                        $q->where('created_by', $user->id);
                    })->where('status', 'cancelled')->count(),
                ];

                $today_entries = ServiceEntry::whereHas('customer', function ($q) use ($user) {
                    $q->where('created_by', $user->id);
                })->whereDate('created_at', Carbon::today())->count();

                $this_month_entries = ServiceEntry::whereHas('customer', function ($q) use ($user) {
                    $q->where('created_by', $user->id);
                })->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count();

                $total_revenue = ServiceEntry::whereHas('customer', function ($q) use ($user) {
                    $q->where('created_by', $user->id);
                })->where('status', 'completed')->sum('total_bill');

                $today_revenue = ServiceEntry::whereHas('customer', function ($q) use ($user) {
                    $q->where('created_by', $user->id);
                })->where('status', 'completed')
                    ->whereDate('created_at', Carbon::today())
                    ->sum('total_bill');

                $inactive_customers = Customer::where('is_active', 0)
                    ->where('created_by', $user->id)
                    ->count();

                $pending_over_7_days = ServiceEntry::whereHas('customer', function ($q) use ($user) {
                    $q->where('created_by', $user->id);
                })->where('status', 'pending')
                    ->whereDate('created_at', '<=', Carbon::now()->subDays(7))
                    ->count();
            } else {
                return errorResponse('Access denied', [], 403);
            }

            $data = [
                'total_customers'      => $total_customers,
                'total_services'       => $total_services,
                'total_entries'        => $total_entries,
                'entries_by_status'    => $entries_by_status,
                'today_entries'        => $today_entries,
                'this_month_entries'   => $this_month_entries,
                'total_revenue'        => $total_revenue,
                'today_revenue'        => $today_revenue,
                'inactive_customers'   => $inactive_customers,
                'pending_over_7_days'  => $pending_over_7_days,
            ];

            return successResponse("Dashboard reports fetched successfully.", $data);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }
}
