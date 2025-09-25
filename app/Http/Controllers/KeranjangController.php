<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class KeranjangController extends Controller 
{
    public function index() 
    {
        $items = session()->get('keranjang', []);
        return view('keranjang.index', compact('items'));
    }

    public function tambah(Request $request, $id)
{
    $produk = Produk::findOrFail($id);
    $keranjang = session()->get('keranjang', []);

    if (isset($keranjang[$id])) {
        if ($keranjang[$id]['qty'] < $produk->stok) {
            $keranjang[$id]['qty'] += 1;
        } else {
            return redirect()->route('keranjang.index')->with('success', 'Jumlah produk sudah mencapai batas stok!');
        }
    } else {
        $keranjang[$id] = [
            'nama' => $produk->nama,
            'harga' => $produk->harga,
            'foto' => $produk->foto,
            'qty' => 1,
            'stok' => $produk->stok, // simpan stok
        ];
    }

    session()->put('keranjang', $keranjang);
    return redirect()->route('keranjang.index')->with('success', 'Produk ditambahkan ke keranjang!');
}

    public function update(Request $request, $id)
{
    $keranjang = session()->get('keranjang', []);
    if (!isset($keranjang[$id])) return redirect()->back();

    $aksi = $request->input('aksi');
    $produk = Produk::findOrFail($id);
    $maxQty = $produk->stok;

    if ($aksi == 'tambah') {
        if ($keranjang[$id]['qty'] < $maxQty) {
            $keranjang[$id]['qty'] += 1;
        } else {
            return back()->with('success', 'Stok tidak mencukupi!');
        }
    } elseif ($aksi == 'kurang') {
        $keranjang[$id]['qty'] -= 1;
        if ($keranjang[$id]['qty'] <= 0) {
            unset($keranjang[$id]);
        }
    }

    session()->put('keranjang', $keranjang);
    return redirect()->route('keranjang.index');
}

    public function hapus($id)
    {
        $keranjang = session()->get('keranjang', []);
        unset($keranjang[$id]);
        session()->put('keranjang', $keranjang);
        
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

}
