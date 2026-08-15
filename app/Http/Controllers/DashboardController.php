<?php

namespace App\Http\Controllers;

use App\Models\{ActivityLog, Product, StockIn, StockOut};

class DashboardController extends Controller
{
    public function __invoke()
    {
        $date = now()->toDateString();

        $labels = [];
        $incomingData = [];
        $outgoingData = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $labels[] = $day->translatedFormat('d M');

            $incomingData[] = (int) StockIn::whereDate('date', $day->toDateString())->sum('quantity');
            $outgoingData[] = (int) StockOut::whereDate('date', $day->toDateString())->sum('quantity');
        }

        return view('dashboard', [
            'totalProducts' => Product::count(),
            'lowStock' => Product::whereColumn('stock', '<=', 'min_stock')->count(),
            'incomingToday' => StockIn::whereDate('date', $date)->sum('quantity'),
            'outgoingToday' => StockOut::whereDate('date', $date)->sum('quantity'),
            'lowProducts' => Product::with('category')->whereColumn('stock', '<=', 'min_stock')->orderBy('stock')->take(6)->get(),
            'activities' => ActivityLog::with('user')->latest()->take(8)->get(),
            'chartLabels' => $labels,
            'chartIncoming' => $incomingData,
            'chartOutgoing' => $outgoingData,
        ]);
    }
}