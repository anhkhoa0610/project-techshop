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
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CancelController;
use App\Http\Controllers\SpecController;


//trang chủ của tui, đụng vào nhớ xin phép =))
Route::prefix('index')->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('index');
    Route::get('/categories/{category_id?}', [IndexController::class, 'categories'])->name('index.categories');
});

Route::middleware(['checkrole:Admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('charts');;
    })->name('dashboard');
    Route::get('/charts', [ChartController::class, 'index'])->name('charts');
    
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

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/search/autocomplete', [UserController::class, 'search'])->name('users.search');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::get('/{user}/show', [UserController::class, 'show'])->name('users.show');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    });

    Route::prefix('specs')->group(function () {
        Route::get('/', [SpecController::class, 'list'])->name('specs.list');
    });
});



// xóa đơn hàng
Route::delete('/orders/{id}', [OrderController::class, 'deleteOrder'])->name('orders.delete');


// Xem giỏ hàng


Route::middleware(['auth'])->group(function () {
    // Trang Giỏ hàng
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    
    // API: Xử lý AJAX Xóa sản phẩm
    Route::delete('/cart/remove/{cartId}', [CartController::class, 'delete'])->name('cart.delete'); 
    
    // API: Xử lý AJAX Cập nhật số lượng
    Route::post('/cart/update/{cartId}', [CartController::class, 'updateQuantity'])->name('cart.update_quantity');

    // Xử lý Form Checkout - Route này nhận dữ liệu JSON từ form
    Route::post('/pay', [CartController::class, 'handleCheckout'])->name('pay.checkout'); 
   
});
Route::post('/checkout', [CheckoutController::class, 'handleCheckout'])->name('checkout'); 
Route::get('/cancel', [OrderController::class, 'show'])->name('cancel');
Route::get('/details/{id}', [OrderController::class, 'showOrderdetails'])->name('details.show');
// thêm vào giỏ hàng
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

// Thanh toán (gửi dữ liệu POST từ giỏ hàng)

// Trang hóa đơn
Route::get('/hoadon', [HoaDonController::class, 'index'])->name('hoadon.index');



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

Route::get('/vnpay/return', [VnpayController::class, 'vnpay_return'])->name('vnpay.return');

Route::prefix('voucher')->group(function () {
    Route::get('/', [VoucherController::class, 'list'])->name('voucher.list');
});

// login routes //
Route::get('/export/invoice/{orderId}/xlsx', [App\Http\Controllers\ExportController::class, 'exportInvoice'])->name('export.invoice.xlsx');

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

Route::get('reset-password/{token}', [LoginController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [LoginController::class, 'resetPassword'])->name('password.update');

Route::post('/api/voucher/check', [App\Http\Controllers\VoucherController::class, 'checkVoucher']);

Route::get('/promotions', [PromotionController::class, 'index'])->name('promotion.index');
// Route::prefix('reviews')->group(function () {
//     Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');
//     Route::get('/create', [ReviewController::class, 'create'])->name('reviews.create');
//     Route::post('/', [ReviewController::class, 'store'])->name('reviews.store');
//     Route::get('/{reviewId}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
//     Route::put('/{reviewId}', [ReviewController::class, 'update'])->name('reviews.update');
//     Route::delete('/{review_id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
// });

// Route cho trang danh sách tin tức
Route::get('/tin-tuc', [PostController::class, 'index'])->name('posts.index');

// Route cho trang chi tiết (ví dụ: /tin-tuc/123)
Route::get('/tin-tuc/{post}', [PostController::class, 'show'])->name('posts.show');

// User Profile Routes
Route::middleware(['auth'])->group(function () {
    // Avatar routes
    Route::post('/profile/avatar', [UserProfileController::class, 'updateAvatar'])
        ->name('profile.avatar.update');
        
    Route::delete('/profile/avatar', [UserProfileController::class, 'removeAvatar'])
        ->name('profile.avatar.remove');
        
    // Profile update route
    Route::post('/profile/update', [UserProfileController::class, 'updateProfile'])
        ->name('profile.update');
    Route::get('/user/profile', [UserController::class, 'showProfile'])->name('user.profile');
    Route::get('/user/edit', [UserController::class, 'showEditProfile'])->name('user.editProfile');
    Route::put('/user/edit', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    Route::get('/user/change-password', [UserController::class, 'showChangePassword'])->name('user.changePassword');
    Route::put('/user/change-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
    Route::delete('/user/delete', [UserController::class, 'destroyProfile'])->name('user.delete');
});

Route::get('/supplier-ui/{id}', [SupplierController::class, 'indexView'])->name('supplier.ui');
