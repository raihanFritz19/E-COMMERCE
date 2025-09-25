<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KonfirmasiPembayaran;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index(Request $request, $trx_id = null)
{
    $trx_id = $trx_id ?? $request->query('trx_id');

    $order = null;
    if ($trx_id) {
        $order = Order::with('items')->where('id', $trx_id)->where('user_id', auth()->id())->first();
        if (!$order) {
            return redirect()->route('keranjang.index')->with('error', 'Pesanan tidak ditemukan.');
        }
    }

    // Fallback: tampilkan form checkout dari session cart
    $cart = session('cart', []);
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['qty'] * $item['harga'];
    }
    session(['total_belanja' => $total]);

    return view('order.index', [
        'total' => $total,
        'cart' => $cart,
        'trx_id' => $trx_id,
        'order' => $order, // <-- INI PENTING
    ]);
}

    public function store(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang masih kosong.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        // Save Order data
        $order = Order::create([
            'id' => Str::uuid(),
            'user_id' => auth()->id(),
            'tanggal' => now(),
            'subtotal' => $total,
            'status' => 'pending',
            'nama' => $request->nama ?? auth()->user()->name,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
        ]);

        // Save each item to OrderItem
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'produk_id' => $item['produk_id'],
                'harga' => $item['harga'],
                'jumlah' => $item['qty'],
            ]);
        }

        // Clear session cart after order is saved
        session()->forget('cart');
        session()->forget('total_belanja');

        // Redirect ke halaman konfirmasi pembayaran dengan membawa trx_id
        return redirect()->route('order.index', [
            'trx_id' => $order->id,
        ])->with('success', 'Pesanan berhasil dibuat.');
    }

    public function konfirmasi(Request $request)
    {
        $request->validate([
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'trx_id' => 'required|exists:orders,id', // pastikan order_id wajib
        ]);

        $path = $request->file('bukti')->store('bukti', 'public');

        KonfirmasiPembayaran::create([
            'user_id' => auth()->id(),
            'order_id' => $request->trx_id,
            'nama_penyetor' => auth()->user()->name,
            'bukti' => $path,
        ]);

        return redirect()->back()->with('success', 'Konfirmasi pembayaran berhasil dikirim.');
    }

    public function showKonfirmasi($order_id)
    {
        $order = Order::findOrFail($order_id);

        return view('order.index', [
            'order' => $order,
            'trx_id' => $order_id,
        ]);
    }

    public function riwayat()
    {
        $orders = Order::with(['konfirmasiPembayaran', 'pengiriman', 'items'])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('order.riwayat', compact('orders'));
    }
}