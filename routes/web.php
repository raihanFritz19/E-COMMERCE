<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\CustomerLoginController;
use App\Http\Controllers\Auth\CustomerRegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\AdminForgotPasswordController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\TestimoniController;
use App\Models\KonfirmasiPembayaran;
use App\Models\ChatLogController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\PengirimanController;
use App\Http\Controllers\Admin\PenjualanController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PasswordController as AuthPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;

// Halaman awal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Produk umum
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');

Route:: get('/storage-link', function(){
    $targetFolder= storage_path('app/public');
    $linkFolder = $_SERVER['DOCUMENT_ROOT']. '/storage';
    symlink($targetFolder, $linkFolder);
    
});

// Keranjang umum
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::patch('/keranjang/update/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');

// Customer Auth
Route::get('/customer/login', [CustomerLoginController::class, 'showLoginForm'])->name('customer.login');
Route::post('/customer/login', [CustomerLoginController::class, 'login']);
Route::get('/customer/register', [CustomerRegisterController::class, 'showRegisterForm'])->name('customer.register');
Route::post('/customer/register', [CustomerRegisterController::class, 'register']);
Route::post('/chatbot', [ChatbotController::class, 'chat'])->name('chatbot');
Route::post('/tawk/webhook', [ChatbotController::class, 'webhook']);
// Halaman setelah login customer
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan.index');
    Route::get('/ubah-password', [UserController::class, 'editPassword'])->name('password.edit');
    Route::post('/ubah-password', [UserController::class, 'updatePassword'])->name('password.update.custom');
    Route::post('/testimoni', [TestimoniController::class, 'store'])->name('testimoni.store');
    Route::post('/pengiriman/{id}/terima', [PengirimanController::class, 'terimaOlehCustomer'])
    ->middleware('auth')
    ->name('pengiriman.terima');

});

// Admin Auth
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login.form');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login');
    Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    Route::get('/lupa-password', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('/lupa-password', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/admin/reset-password/{token}', [NewPasswordController::class, 'create'])->name('admin.password.reset');
    Route::post('/admin/reset-password', [NewPasswordController::class, 'store'])->name('admin.password.update');
});

// Admin Panel
Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('/produk', ProdukController::class)->names('admin.produk');
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('admin.transaksi.index');
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('admin.transaksi.show'); // ← Tambahan
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('admin.penjualan.index');
    Route::get('/admin/penjualan/kalender', [PenjualanController::class, 'calendar'])->name('admin.penjualan.kalender');
    Route::get('/admin/penjualan/print', [PenjualanController::class, 'print'])->name('penjualan.print');
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('admin.pelanggan.index');
    Route::resource('pelanggan', PelangganController::class)->names('admin.pelanggan');
    Route::patch('/admin/transaksi/{id}/selesai', [TransaksiController::class, 'selesaikan'])->name('admin.transaksi.selesaikan');   
    Route::put('/pembayaran/{id}', [PembayaranController::class, 'update'])->name('admin.pembayaran.update');
    Route::delete('/admin/pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('admin.pembayaran.destroy');
    Route::resource('pengiriman', PengirimanController::class)->only(['index', 'show'])->names('admin.pengiriman');
    Route::post('/pengiriman', [PengirimanController::class, 'store'])->name('admin.pengiriman.store');
    Route::post('/pengiriman/{id}/status', [PengirimanController::class, 'updateStatus'])->name('admin.pengiriman.updateStatus');
    Route::get('/get-orders/{user_id}', [PengirimanController::class, 'getOrders']);
    Route::get('/log-transaksi-gagal', function () { 
        $logs = \App\Models\FailedTransaction::latest()->paginate(20);
        return view('admin.logs.failed', compact('logs'));
    })->name('admin.log.failed');
    // routes/web.php
    Route::get('/admin/pengiriman/{id}/tracking-data', [PengirimanController::class, 'getTrackingData']);
    Route::get('/chat-logs', [\App\Http\Controllers\Admin\ChatLogController::class, 'index'])->name('admin.chatlogs.index');
    Route::post('/chat-logs/import', [\App\Http\Controllers\Admin\ChatLogController::class, 'import'])->name('admin.chatlogs.import');
});



// Profil user + checkout
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/keranjang/clear', function () { 
        session()->forget('keranjang');
        return response()->json(['message' => 'Keranjang dikosongkan']);
    })->name('keranjang.clear');


    // Checkout & Order
    Route::get('/checkout', [OrderController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');
    // Order & Konfirmasi
    Route::get('/order/konfirmasi/{trx_id?}', [OrderController::class, 'index'])->name('order.index');
    
    // khusus submit konfirmasi pembayaran
    Route::post('/order/konfirmasi', [OrderController::class, 'konfirmasi'])->name('order.konfirmasi');
    
    // kalau butuh GET halaman konfirmasi (optional)
    Route::get('/order/konfirmasi', [OrderController::class, 'index'])->name('order.konfirmasi.show');
    Route::post('/checkout/ongkir', [CheckoutController::class, 'cekOngkirManual'])->name('checkout.ongkir');
    Route::get('/riwayat', [OrderController::class, 'riwayat'])->name('order.riwayat');

    // Midtrans Snap token
    Route::post('/checkout/token', [CheckoutController::class, 'getSnapToken'])->name('checkout.snap');
});

// Midtrans callback (tidak pakai auth, karena dipanggil oleh server Midtrans)
Route::middleware('web')->post('/midtrans/callback', [CheckoutController::class, 'callback'])->name('midtrans.callback');

// routes/web.php
Route::get('/chatbase/identity', function() {
    $user = auth()->user();
    $secret = env('CHATBASE_SECRET');
    $userId = strval($user->id);
    $hash = hash_hmac('sha256', $userId, $secret);
    return [
        'userId' => $userId,
        'hash' => $hash,
        'name' => $user->name,
        'email' => $user->email,
    ];
})->middleware('auth');

// Tes Email
Route::get('/tes-email', function () {
    Mail::raw('Ini adalah email percobaan dari Laravel', function ($message) {
        $message->to('test@example.com')->subject('Tes Email Laravel');
    });
    return 'Email sudah dikirim (cek inbox Mailtrap)';
});

// Reset password
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.reset.custom');

// Auth Laravel
require __DIR__ . '/auth.php';
