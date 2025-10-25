<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('layouts.dashboard');
});

Route::get('/index', [IndexController::class, 'index'])->name('index');

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'list'])->name('products.list');
    // Sau này bạn có thể thêm các route khác:
    // Route::get('/create', [ProductController::class, 'create'])->name('product.create');
    // Route::post('/store', [ProductController::class, 'store'])->name('product.store');
});
Route::prefix('supplier')->group(function () {
    Route::get('/', [SupplierController::class, 'list'])->name('supplier.list');

});
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'list'])->name('categories.list');

});

Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'list'])->name('orders.list');

});

Route::prefix('orderDetails')->group(function () {
    Route::get('/{order_id}', [OrderDetailController::class, 'list'])->name('orderDetails.list');

});


// Xem giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Thanh toán (gửi dữ liệu POST từ giỏ hàng)
Route::post('/pay', [PayController::class, 'index'])->name('pay.index');

// Trang hóa đơn
Route::get('/hoadon', [HoaDonController::class, 'index'])->name('hoadon.index');

// Xóa 1 hoặc nhiều sản phẩm trong giỏ
Route::delete('/cart/{cart_id}', [CartController::class, 'destroy']);
Route::delete('/cart-items', [CartController::class, 'destroyMany'])->name('cart.destroyMany');

Route::prefix('voucher')->group(function () {
    Route::get('/', [VoucherController::class, 'list'])->name('voucher.list');
});


Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/search/autocomplete', [UserController::class, 'search'])->name('users.search');
});

// login routes //
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('user.authUser');

Route::get('register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('register', [LoginController::class, 'postUser'])->name('register.postUser');

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/reset', [LoginController::class, 'showResetForm'])->name('reset.form');
Route::post('/reset', [LoginController::class, 'reset'])->name('reset');

Route::get('/forgot', [LoginController::class, 'showForgotForm'])->name('forgot.form');
Route::post('/forgot', [LoginController::class, 'forgot'])->name('forgot');

Route::get('reset-password/{token}', function ($token) {
    return view('login.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('reset-password', function (Illuminate\Http\Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = \Hash::make($password);
            $user->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Password reset successfully.')
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.update');

