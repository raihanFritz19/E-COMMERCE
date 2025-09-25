<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Testimoni;

class HomeController extends Controller
{
    public function index()
    {
        $produkTerbaru = Produk::latest()->take(3)->get();

        $kueBasah = Produk::where('kategori', 'kue basah')->get();
        $kueKering = Produk::where('kategori', 'kue kering')->get();
        $kueTradisional = Produk::where('kategori', 'kue tradisional')->get();

        $testimonis = Testimoni::with(['user', 'produk'])
                            ->latest()
                            ->take(6)
                            ->get();

        return view('home', compact(
            'produkTerbaru',
            'kueBasah',
            'kueKering',
            'kueTradisional',
            'testimonis' // <-- ditambahkan
        ));
    }
}
