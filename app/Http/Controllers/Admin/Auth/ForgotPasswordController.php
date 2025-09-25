<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller 
{
    use SendsPasswordResetEmails;

    public function showLinkRequestForm() 
    {
        return view('admin.auth.password.email');
    }

    public function broker() 
    {
        return Password::broker('users'); // kita pakai broker users
    }
}
