<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengiriman;
use App\Models\Order; 
use App\Models\KonfirmasiPembayaran;

class PengirimanController extends Controller
{
    public function index()
    {
        // Ambil semua pengiriman dengan relasi user dan order, urutkan berdasarkan created_at
        $pengirimen = Pengiriman::with(['user', 'order'])
            ->orderByDesc('created_at')
            ->paginate(10);

        // Ambil semua user yang punya order selesai, dan ambil order-nya juga
        $usersSiapKirim = KonfirmasiPembayaran::where('status', 'selesai')
            ->with(['user.orders' => function($query) {
                $query->where('status', 'selesai');
            }])
            ->get();

        return view('admin.pengiriman.index', compact('pengirimen', 'usersSiapKirim'));
    }

    public function show($id)
    {
        $pengiriman = Pengiriman::with('user')->findOrFail($id);
        return view('admin.pengiriman.show', compact('pengiriman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
            'kurir'   => 'required|string',
        ]);

        // Ambil order terakhir dari user dengan status selesai
        $order = Order::findOrFail($request->order_id);

        if (!$order) {
            return back()->with('error', 'Alamat tidak ditemukan. User belum punya order selesai.');
        }

        // Cegah duplikasi pengiriman
        $sudahAda = Pengiriman::where('user_id', $request->user_id)
                          ->where('order_id', $request->order_id)
                          ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Pengiriman untuk order ini sudah pernah dibuat.');
        }

        Pengiriman::create([
            'user_id' => $request->user_id,
            'order_id' => $request->order_id, 
            'alamat'  => $order->alamat,     
            'kurir'   => $request->kurir,
            'status'  => 'belum_dikirim',
        ]);

        return redirect()
            ->route('admin.pengiriman.index')
            ->with('success', 'Pengiriman berhasil dibuat.');
    }

    public function updateStatus($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);

        if ($pengiriman->status === 'belum_dikirim') {
            $pengiriman->status = 'dikirim';
            $pengiriman->save();
            return redirect()
                ->route('admin.pengiriman.show', $pengiriman->id)
                ->with('success', 'Status diperbarui menjadi "dalam pengiriman".');
        }

        return redirect()
            ->route('admin.pengiriman.show', $pengiriman->id)
            ->with('error', 'Admin hanya boleh ubah dari Belum Dikirim ke Dalam Pengiriman.');
    }

    public function terimaOlehCustomer($id)
    {
        $pengiriman = Pengiriman::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($pengiriman->status !== 'dikirim') {
            return back()->with('error', 'Status belum dalam pengiriman.');
        }

        $pengiriman->status = 'diterima';
        $pengiriman->save();

        return back()->with('success', 'Pengiriman dikonfirmasi diterima.');
    }

    // KODE INI UNTUK AMBIL MENGAMBIL DATA DI DROPDOWN PILIH ORDER
    public function getOrders($user_id)
    {
        $orders = Order::where('user_id', $user_id)->get();

        $ordersTerkirim = Pengiriman::pluck('order_id')->toArray(); 

        return $orders->map(function ($order) use ($ordersTerkirim) {
            return [
                'id' => $order->id,
                'nama' => $order->user->name,
                'alamat' => $order->alamat,
                'is_terkirim' => in_array($order->id, $ordersTerkirim), 
            ];
        });
    }

    // Method to get tracking data for a specific pengiriman
    public function getTrackingData($id)
    {
        $pengiriman = Pengiriman::with('trackings')->findOrFail($id);

        return response()->json(
            $pengiriman->trackings->map(function ($item) {
                return [
                    'lokasi' => $item->lokasi,
                    'waktu' => $item->created_at->format('d-m-Y H:i'),
                ];
            })
        );
    }
}
