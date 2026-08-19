<?php

namespace App\Http\Controllers;

use App\Models\{ActivityLog, Product, StockIn, StockOut};

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $date = now()->toDateString();

        $labels = [];
        $incomingData = [];
        $outgoingData = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);

            $labels[] = $day->translatedFormat('d M');

            $incomingData[] = (int) StockIn::whereDate(
                'date',
                $day->toDateString()
            )->sum('quantity');

            $outgoingData[] = (int) StockOut::whereDate(
                'date',
                $day->toDateString()
            )->sum('quantity');
        }

        if ($user->isRole('staff_gudang')) {
            return view('dashboard', [
                'role' => 'staff_gudang',

                'incomingToday' => StockIn::with('product')
                    ->whereDate('date', $date)
                    ->latest()
                    ->get(),

                'outgoingToday' => StockOut::with('product')
                    ->whereDate('date', $date)
                    ->latest()
                    ->get(),

                'chartLabels' => $labels,
                'chartIncoming' => $incomingData,
                'chartOutgoing' => $outgoingData,
            ]);
        }

        return view('dashboard', [
            'role' => $user->role,

            'totalProducts' => Product::count(),

            'lowStock' => Product::whereColumn(
                'stock',
                '<=',
                'min_stock'
            )->count(),

            'incomingTodayCount' => StockIn::whereDate(
                'date',
                $date
            )->sum('quantity'),

            'outgoingTodayCount' => StockOut::whereDate(
                'date',
                $date
            )->sum('quantity'),

            'lowProducts' => Product::with('category')
                ->whereColumn('stock', '<=', 'min_stock')
                ->orderBy('stock')
                ->take(6)
                ->get(),

            'activities' => $user->isRole('admin')
                ? ActivityLog::with('user')->latest()->take(8)->get()
                : collect(),

            'chartLabels' => $labels,
            'chartIncoming' => $incomingData,
            'chartOutgoing' => $outgoingData,
        ]);
    }
}
