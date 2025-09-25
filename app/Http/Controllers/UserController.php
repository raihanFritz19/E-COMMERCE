<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function editPassword()
{
    return view('user.ubah-password');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'old_password' => ['required', 'current_password'],
        'new_password' => ['required', 'confirmed', 'min:8'],
    ]);

    $user = $request->user();
    $user->password = bcrypt($request->new_password);
    $user->save();

    return back()->with('success', 'Password berhasil diperbarui.');
}
}
