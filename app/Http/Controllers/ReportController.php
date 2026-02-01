<?php

namespace App\Http\Controllers;

use App\Enums\PeriodType;
use App\Models\Customer;
use App\Models\ServiceEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dailyEntries()
    {
        $user = auth()->user();
        if (!$user->isAdmin()) return response()->json(['message' => 'Access denied'], 403);

        $today = Carbon::today();
        $entries = ServiceEntry::with(['customer', 'service'])
            ->whereDate('created_at', $today)
            ->get();

        return successResponse('daily_entries', $entries);
    }

    public function customerWise(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin()) return response()->json(['message' => 'Access denied'], 403);

        $type = $request->input('type', PeriodType::DAILY);
        $now = Carbon::now();

        $customers = Customer::where('is_active', 1)->get();
        $reports = [];

        foreach ($customers as $customer) {
            $query = ServiceEntry::where('customer_id', $customer->id);
            if ($type === 'daily') $query->whereDate('created_at', Carbon::today());
            elseif ($type === 'monthly') $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
            elseif ($type === 'yearly') $query->whereYear('created_at', $now->year);

            $reports[] = [
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'total_bill' => $query->sum('total_bill'),
            ];
        }

        return successResponse('customer_reports', $reports);
    }

    public function pendingBills(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin()) return response()->json(['message' => 'Access denied'], 403);

        $type = $request->input('type', 'all'); // daily, monthly, yearly, all
        $customerId = $request->input('customer_id', null);
        $query = ServiceEntry::with(['customer:id,name,created_at', 'service:id,name'])
            ->where('status', 'pending');

        if ($customerId) $query->where('customer_id', $customerId);
        $now = Carbon::now();

        if ($type === 'daily') $query->whereDate('created_at', Carbon::today());
        elseif ($type === 'monthly') $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
        elseif ($type === 'yearly') $query->whereYear('created_at', $now->year);

        $entries = $query->get();
        return successResponse('Pending bills', $entries);
    }

    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin()) return response()->json(['message' => 'Access denied'], 403);

        $type = $request->input('type', 'daily'); // daily, monthly, yearly
        $report = $request->input('report', 'service_entry'); // service_entry, customer, pending
        $customerId = $request->input('customer_id', null);

        $query = ServiceEntry::with(['customer', 'service']);
        $now = Carbon::now();

        if ($customerId) $query->where('customer_id', $customerId);
        if ($report === 'pending') $query->where('status', 'pending');

        if ($type === 'daily') $query->whereDate('created_at', Carbon::today());
        elseif ($type === 'monthly') $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
        elseif ($type === 'yearly') $query->whereYear('created_at', $now->year);

        $entries = $query->get();

        $pdf = Pdf::loadView('reports.dynamic', [
            'entries' => $entries,
            'report_type' => $report,
            'date_type' => $type,
            'customer_id' => $customerId,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'report_' . now()->format('Y_m_d_H_i_s') . '.pdf');
    }
}
