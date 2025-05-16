<?php

namespace App\Exports;

use App\Models\Delivery;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportExport implements FromView
{
    public function view(): View
    {
        // Latest 20 deliveries with client and driver relationships
        $deliveries = Delivery::with(['client', 'driver'])->latest()->take(20)->get();

        // Total earnings from all deliveries
        $totalEarnings = Delivery::sum('price');

        // Top 5 drivers based on number of deliveries
        $topDrivers = Driver::withCount('deliveries')
            ->orderByDesc('deliveries_count')
            ->take(5)
            ->get();

        // Top 5 clients based on number of deliveries (users table)
        $topClients = User::withCount('deliveries')
            ->orderByDesc('deliveries_count')
            ->take(5)
            ->get();

        return view('admin.reports.excel', compact(
            'deliveries',
            'totalEarnings',
            'topDrivers',
            'topClients'
        ));
    }
}
