<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\LegalPageController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\StoreSettingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController as PublicReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/product-details/{id}', [ProductController::class, 'details'])->name('products.details');
Route::post('/product/{product}/reviews', [PublicReviewController::class, 'store'])->middleware('throttle:20,1')->name('reviews.store');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/job/{slug}', [JobController::class, 'show'])->name('jobs.show');
Route::post('/jobs/{job}/apply', [JobController::class, 'apply'])->middleware('throttle:10,1')->name('jobs.apply');

Route::get('/checkout/{productId}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/submit', [CheckoutController::class, 'submit'])->middleware('throttle:30,30')->name('checkout.submit');

Route::get('/order/track', [OrderController::class, 'track'])->name('order.track');
Route::post('/order/track', [OrderController::class, 'search'])->middleware('throttle:60,1')->name('order.search');

Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/refund-policy', [PageController::class, 'refundPolicy'])->name('refund-policy');
Route::get('/about', [PageController::class, 'about'])->name('about');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:30,1')->name('login.submit');
    });

    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/toggle', [AdminProductController::class, 'toggle'])->name('products.toggle');
        Route::post('/products/reorder', [AdminProductController::class, 'reorder'])->name('products.reorder');

        Route::get('/jobs', [AdminJobController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/create', [AdminJobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [AdminJobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}/edit', [AdminJobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [AdminJobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [AdminJobController::class, 'destroy'])->name('jobs.destroy');
        Route::post('/jobs/{job}/toggle', [AdminJobController::class, 'toggle'])->name('jobs.toggle');
        Route::get('/job-applications', [AdminJobController::class, 'applications'])->name('jobs.applications');
        Route::put('/job-applications/{application}', [AdminJobController::class, 'updateStatus'])->name('jobs.applications.status');

        Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
        Route::get('/banners/create', [BannerController::class, 'create'])->name('banners.create');
        Route::post('/banners', [BannerController::class, 'store'])->name('banners.store');
        Route::get('/banners/{banner}/edit', [BannerController::class, 'edit'])->name('banners.edit');
        Route::put('/banners/{banner}', [BannerController::class, 'update'])->name('banners.update');
        Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');
        Route::post('/banners/{banner}/toggle', [BannerController::class, 'toggle'])->name('banners.toggle');
        Route::post('/banners/reorder', [BannerController::class, 'reorder'])->name('banners.reorder');

        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::post('/reviews/{review}/toggle', [ReviewController::class, 'toggle'])->name('reviews.toggle');
        Route::post('/reviews/{review}/feature', [ReviewController::class, 'feature'])->name('reviews.feature');
        Route::post('/reviews/reorder', [ReviewController::class, 'reorder'])->name('reviews.reorder');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/verify', [AdminOrderController::class, 'verify'])->name('orders.verify');
        Route::post('/orders/{order}/reject', [AdminOrderController::class, 'reject'])->name('orders.reject');
        Route::post('/orders/{order}/deliver', [AdminOrderController::class, 'deliver'])->name('orders.deliver');
        Route::post('/orders/{order}/note', [AdminOrderController::class, 'addNote'])->name('orders.add-note');
        Route::post('/orders/{order}/notes', [AdminOrderController::class, 'updateNotes'])->name('orders.update-notes');

        Route::get('/payment-settings', [PaymentSettingController::class, 'index'])->name('payment-settings.index');
        Route::put('/payment-settings/{method}', [PaymentSettingController::class, 'update'])->name('payment-settings.update');
        Route::post('/payment-settings/{method}/toggle', [PaymentSettingController::class, 'toggle'])->name('payment-settings.toggle');

        Route::get('/social-links', [SocialLinkController::class, 'index'])->name('social-links.index');
        Route::post('/social-links', [SocialLinkController::class, 'store'])->name('social-links.store');
        Route::put('/social-links/{socialLink}', [SocialLinkController::class, 'update'])->name('social-links.update');
        Route::delete('/social-links/{socialLink}', [SocialLinkController::class, 'destroy'])->name('social-links.destroy');
        Route::post('/social-links/{socialLink}/toggle', [SocialLinkController::class, 'toggle'])->name('social-links.toggle');
        Route::post('/social-links/reorder', [SocialLinkController::class, 'reorder'])->name('social-links.reorder');

        Route::get('/store-settings', [StoreSettingController::class, 'index'])->name('store-settings.index');
        Route::put('/store-settings', [StoreSettingController::class, 'update'])->name('store-settings.update');

        Route::get('/legal-pages', [LegalPageController::class, 'index'])->name('legal-pages.index');
        Route::get('/legal-pages/create', [LegalPageController::class, 'create'])->name('legal-pages.create');
        Route::post('/legal-pages', [LegalPageController::class, 'store'])->name('legal-pages.store');
        Route::get('/legal-pages/{legalPage}/edit', [LegalPageController::class, 'edit'])->name('legal-pages.edit');
        Route::put('/legal-pages/{legalPage}', [LegalPageController::class, 'update'])->name('legal-pages.update');
        Route::delete('/legal-pages/{legalPage}', [LegalPageController::class, 'destroy'])->name('legal-pages.destroy');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');
        Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    });
});
