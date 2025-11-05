<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayController;
use App\Http\Controllers\UIProductDetailsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MomoController;
use App\Http\Controllers\VnpayController;

Route::get('/index', [IndexController::class, 'index'])->name('index');
Route::middleware(['checkrole:Admin'])->group(function () {
    Route::get('/', function () {
        return view('layouts.dashboard');
    })->name('dashboard');
    Route::prefix('supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'list'])->name('supplier.list');
    });
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'list'])->name('products.list');
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

    Route::prefix('voucher')->group(function () {
        Route::get('/', [VoucherController::class, 'list'])->name('voucher.list');
    });

    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/create', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/{reviewId}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
        Route::get('/{reviewId}/view', [ReviewController::class, 'view'])->name('reviews.view');
        Route::put('/{reviewId}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/{review_id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });
});




// Xem giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// thêm vào giỏ hàng
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

// Thanh toán (gửi dữ liệu POST từ giỏ hàng)
Route::post('/pay', [CheckoutController::class, 'handleCheckout'])->name('pay.checkout');

// Trang hóa đơn
Route::get('/hoadon', [HoaDonController::class, 'index'])->name('hoadon.index');

// Xóa 1 hoặc nhiều sản phẩm trong giỏ
Route::delete('/cart/{cart_id}', [CartController::class, 'destroy']);
Route::delete('/cart-items', [CartController::class, 'destroyMany'])->name('cart.destroyMany');

Route::get('/product-details/{id}', [UIProductDetailsController::class, 'show'])->name('product.details');
// Redirect nếu không có id
Route::get('/product-details', function () {
    return redirect()->route('index');
});

Route::post('/momo/payment', [MomoController::class, 'momo_payment'])->name('momo.payment');
Route::post('/vnpay/payment', [VnpayController::class, 'vnpay_payment'])->name('vnpay.payment');

// 🟢 Khi thanh toán xong, MoMo redirect người dùng về link này
Route::get('/momo/return', [MomoController::class, 'momo_return'])->name('momo.return');

// 🟣 MoMo gọi ngầm (server-to-server) để thông báo trạng thái thanh toán
Route::post('/momo/ipn', [MomoController::class, 'momo_ipn'])->name('momo.ipn');

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/{user}/show', [UserController::class, 'show'])->name('users.show');
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
        'password_confirmation' => 'required|min:6',
    ], [
        'email.required' => 'Vui lòng nhập email của bạn.',
        'email.email' => 'Định dạng email không hợp lệ.',
        'password.required' => 'Vui lòng nhập mật khẩu mới.',
        'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
        'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
        'password_confirmation.min' => 'Xác nhận mật khẩu mới phải có ít nhất 6 ký tự.',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = \Hash::make($password);
            $user->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công.')
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.update');


Route::post('/api/voucher/check', [App\Http\Controllers\VoucherController::class, 'checkVoucher']);

Route::get('/promotions', [PromotionController::class, 'index'])->name('promotion.index');