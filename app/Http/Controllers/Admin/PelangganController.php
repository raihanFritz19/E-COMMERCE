<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = User::where('role', 'customer')->latest()->paginate(10);
        return view('admin.pelanggan.index', compact('pelanggans'));
    }
    public function destroy($id)
{
    $pelanggan = User::findOrFail($id);
    $pelanggan->delete();

    return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
}
}
