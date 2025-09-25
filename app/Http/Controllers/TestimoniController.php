<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimoni;

class TestimoniController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'order_id' => 'required',
        'produk_id' => 'required',
        'isi' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    $cek = Testimoni::where('order_id', $request->order_id)
        ->where('produk_id', $request->produk_id)
        ->first();

    if ($cek) {
        return back()->with('error', 'Testimoni sudah pernah dikirim.');
    }

    Testimoni::create([
        'user_id' => auth()->id(),
        'order_id' => $request->order_id,
        'produk_id' => $request->produk_id,
        'isi' => $request->isi,
        'rating' => $request->rating,
    ]);

    return back()->with('success', 'Testimoni berhasil dikirim.');
}
}