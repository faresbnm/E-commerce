<?php
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

// Authentication routes (already added by laravel/ui)
Auth::routes();

// Public routes
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{gallery}', [GalleryController::class, 'show'])->name('gallery.show');

//test email
Route::get('/test-email', function() {
    try {
        Mail::raw('This is a test email', function($message) {
            $message->to('fbnm8829@gmail.com')
                    ->subject('Test Email');
        });
        return 'Email sent - check your inbox and spam folder';
    } catch (\Exception $e) {
        return 'Error: '.$e->getMessage();
    }
});


//profile
Route::prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/update-password', [ProfileController::class, 'updatePassword'])
    ->name('profile.updatePassword');
    
    Route::post('/address', [ProfileController::class, 'addAddress'])->name('address.add');
    Route::delete('/address/{address}', [ProfileController::class, 'deleteAddress'])->name('address.delete');
    Route::post('/address/{address}/set-default', [ProfileController::class, 'setDefaultAddress'])->name('address.set-default');
});

// Cart routes
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/orders/from-cart', [OrderController::class, 'storeFromCart'])->name('orders.storeFromCart');
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::delete('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    
    // Addresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.set-default');

    // Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])
    ->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'userManagement'])->name('admin.users');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/products/pending', [AdminController::class, 'pendingProducts'])->name('admin.products.pending');
    Route::post('/products/{product}/approve', [AdminController::class, 'approveProduct'])->name('admin.products.approve');
    Route::delete('/products/{product}/reject', [AdminController::class, 'rejectProduct'])->name('admin.products.reject');

    //Gallery Approval
    Route::get('/gallery/pending', [AdminController::class, 'pendingGalleries'])->name('admin.gallery.pending');
    Route::post('/gallery/{gallery}/approve', [AdminController::class, 'approveGallery'])->name('admin.gallery.approve');
    Route::post('/gallery/{gallery}/reject', [AdminController::class, 'rejectGallery'])->name('admin.gallery.reject');
});

// IT/Commercial Routes
Route::middleware(['auth', 'role:it_commercial'])->group(function () {
    Route::get('/ITC/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/ITC/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/ITC/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/ITC/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/ITC/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Gallery routes for events/trade shows
    Route::get('/ITC/gallery/create', [GalleryController::class, 'create'])->name('gallery.create');
    Route::post('/ITC/gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::delete('/ITC/gallery/remove/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

});