<?php

use Illuminate\Support\Facades\Route;

// Public
Route::view('/', 'welcome')->name('home');

// Marketplace (public)
Route::get('/market', \App\Livewire\Market\MarketIndex::class)->name('market.index');
Route::get('/market/{product}', \App\Livewire\Market\ProductShow::class)->name('market.show');

// Authenticated (any role)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

// Buyer only
Route::middleware(['auth', 'verified', 'role:buyer,admin'])->group(function () {
    Route::get('/cart', \App\Livewire\Cart\CartIndex::class)->name('cart.index');
    Route::get('/checkout', \App\Livewire\Checkout\CheckoutIndex::class)->name('checkout.index');
    Route::get('/orders', \App\Livewire\Buyer\OrdersIndex::class)->name('buyer.orders.index');
    Route::get('/orders/{order}', \App\Livewire\Buyer\OrderShow::class)->name('buyer.orders.show');
});

// Vendor only
Route::middleware(['auth', 'verified', 'role:vendor,admin'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/products', \App\Livewire\Vendor\ProductsIndex::class)->name('products.index');
    Route::get('/products/create', \App\Livewire\Vendor\ProductCreate::class)->name('products.create');
    Route::get('/products/{product}/edit', \App\Livewire\Vendor\ProductEdit::class)->name('products.edit');
    Route::get('/orders', \App\Livewire\Vendor\OrdersIndex::class)->name('orders.index');
});

require __DIR__.'/settings.php';
