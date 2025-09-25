<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\KonfirmasiPembayaran;
use Illuminate\Support\Str; 

class PembayaranController extends Controller 
{
    public function index() 
    {
        // Use paginate instead of get to enable pagination
        $pembayarans = KonfirmasiPembayaran::latest()->paginate(5); // 10 items per page
        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function update(Request $request, $id) 
    {
        $pembayaran = KonfirmasiPembayaran::findOrFail($id);

        $pembayaran->status = $request->status;

        // Update atau generate resi
        if ($request->status === 'dikirim' && empty($pembayaran->no_resi)) {
            $pembayaran->no_resi = 'RESI-' . strtoupper(Str::random(10)); 
        } else {
            $pembayaran->no_resi = $request->no_resi;
        }

        $pembayaran->save();

        // (opsional) update status order juga
        if ($pembayaran->order_id) {
            $order = Order::find($pembayaran->order_id);
            if ($order) {
                $order->status = 'dikirim';
                $order->save();
            }
        }

        return redirect()->route('admin.pembayaran.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        KonfirmasiPembayaran::destroy($id);
        return back()->with('success', 'Data berhasil dihapus.');
    }
}
