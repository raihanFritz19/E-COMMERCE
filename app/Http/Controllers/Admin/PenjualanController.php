<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Order; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Order::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('COUNT(*) as jumlah'),
            DB::raw('SUM(subtotal) as total')
        )
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get();
        return view('admin.penjualan.index', compact('penjualans'));
    }

    public function print()
{
    $now = Carbon::now();
    $startOfMonth = $now->copy()->startOfMonth();
    $endOfMonth = $now->copy()->endOfMonth();

    $penjualans = Order::select(
        DB::raw('DATE(created_at) as tanggal'),
        DB::raw('COUNT(*) as jumlah'),
        DB::raw('SUM(subtotal) as total')
    )
    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->groupBy(DB::raw('DATE(created_at)'))
    ->get();


    return Pdf::loadView('admin.penjualan.print', compact('penjualans', 'now'))
        ->setPaper('a4', 'portrait')
        ->download('kalender-penjualan-'.$now->format('F-Y').'.pdf');
}
    
}
