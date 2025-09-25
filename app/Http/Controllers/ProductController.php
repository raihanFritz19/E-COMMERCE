<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class ProductController extends Controller 
{
    public function index(Request $request) 
    {
        $query = Produk::query();

        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }

        $produks = $query->latest()->paginate(9);

        return view('produk.index', compact('produks'));
    }
}
