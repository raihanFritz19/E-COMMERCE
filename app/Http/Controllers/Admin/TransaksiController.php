<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class TransaksiController extends Controller 
{
    public function index(Request $request)
{
    $query = Order::with(['user', 'items'])->orderByDesc('created_at');

    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    $transaksis = $query->paginate(10);

    // Hitung total transaksi yang ditampilkan (berdasarkan halaman saat ini)
    $totalTransaksi = $transaksis->getCollection()->sum(function ($transaksi) {
        return $transaksi->subtotal ?? $transaksi->items->sum(fn($item) => $item->harga * $item->jumlah);
    });

    return view('admin.transaksi.index', compact('transaksis', 'totalTransaksi'));
}


    public function show($id)
    {
        $transaksi = Order::with('user', 'items.produk')->findOrFail($id);
        return view('admin.transaksi.show', compact('transaksi'));
    }
    public function selesaikan($id)
    {
        $transaksi = Order::findOrFail($id);
        $transaksi->update(['status' => 'selesai']);
        
        return redirect()->route('admin.transaksi.index')->with('success', 'Status transaksi diperbarui.');
    }
}
