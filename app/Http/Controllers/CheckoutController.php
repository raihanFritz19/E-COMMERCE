<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\Ongkir;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function getSnapToken(Request $request)
    {
        $user = auth()->user();
        $keranjang = session('keranjang');

        if (!$keranjang || count($keranjang) === 0) {
            return response()->json(['error' => 'Keranjang kosong'], 400);
        }

        DB::beginTransaction();

        try {
            $subtotal = 0;
            foreach ($keranjang as $item) {
                $subtotal += $item['harga'] * $item['qty'];
            }

            $ongkir = intval($request->ongkir ?? 0);
            $total_akhir = $subtotal + $ongkir;

            $order = Order::create([
                'id' => $request->trx_id,
                'user_id' => $user->id,
                'nama' => $user->name,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'subtotal' => $subtotal,
                'ongkir' => $ongkir,
                'total' => $total_akhir,
                'status' => 'pending',
            ]);

            foreach ($keranjang as $id => $item) {
                $produk = Produk::find($id);

                if (!$produk || $produk->stok < $item['qty']) {
                    throw new \Exception("Produk tidak valid atau stok tidak mencukupi.");
                }

                $produk->decrement('stok', $item['qty']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'produk_id' => $id,
                    'nama_produk' => $item['nama'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                ]);
            }

            // Midtrans config
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Buat item details (produk + ongkir)
            $item_details = [];
            foreach ($keranjang as $id => $item) {
                $item_details[] = [
                    'id' => $id,
                    'price' => $item['harga'],
                    'quantity' => $item['qty'],
                    'name' => $item['nama']
                ];
            }
            if ($ongkir > 0) {
                $item_details[] = [
                    'id' => 'ongkir',
                    'price' => $ongkir,
                    'quantity' => 1,
                    'name' => 'Ongkos Kirim'
                ];
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $order->id,
                    'gross_amount' => $total_akhir,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $request->telepon,
                    'shipping_address' => [
                        'address' => $request->alamat,
                        'city' => $request->kota ?? '',
                    ],
                ],
                'item_details' => $item_details,
                'callbacks' => [
                    // setelah selesai pembayaran, redirect ke konfirmasi
                    'finish' => route('order.konfirmasi.show', ['trx_id' => $order->id]),
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            session()->forget('keranjang');
            DB::commit();

            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Checkout Error: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Gagal mendapatkan token pembayaran'], 500);
        }
    }

    // AJAX untuk cek ongkir manual
    public function cekOngkirManual(Request $request)
    {
        $kota = $request->input('kota');
        $ongkir = Ongkir::where('kota', 'like', '%' . $kota . '%')->first();
        return response()->json(['ongkir' => $ongkir ? $ongkir->ongkir : 0]);
    }

    // Midtrans callback
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $signatureKey = hash('sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($signatureKey !== $request->signature_key) {
            return response()->json(['message' => 'Signature tidak valid'], 403);
        }

        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        $order->update(['status' => $request->transaction_status]);

        if (in_array($request->transaction_status, ['cancel', 'expire', 'deny'])) {
            foreach ($order->items as $item) {
                $produk = Produk::find($item->produk_id);
                if ($produk) {
                    $produk->increment('stok', $item->qty);
                }
            }
        } else if ($request->transaction_status == 'settlement') {
            Transaksi::create([
                'user_id' => $order->user_id,
                'tanggal' => now(),
                'total' => $order->total,
                'status' => 'success'
            ]);
        }

        return response()->json(['message' => 'Status diperbarui']);
    }
}
