<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProducerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransformerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public-friendly dashboard alias used by layouts and tests
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/mon-profil', [ConsumerController::class, 'profile'])->name('consumer.profile');
    Route::get('/mon-profil/modifier', [ConsumerController::class, 'edit'])->name('consumer.edit');
    Route::patch('/mon-profil', [ConsumerController::class, 'update'])->name('consumer.update');
    Route::get('/mes-recommandations', [ConsumerController::class, 'recommendations'])->name('consumer.recommendations');
});

Route::get('/produits', [ProductController::class, 'index'])->name('products.index');
Route::get('/produits/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/produits/{product}/trace', [ProductController::class, 'trace'])->name('products.trace');
Route::get('/producteurs', [ProducerController::class, 'index'])->name('producers.index');
Route::get('/producteurs/{producer}', [ProducerController::class, 'show'])->name('producers.show');
Route::get('/transformateurs', [TransformerController::class, 'index'])->name('transformers.index');
Route::get('/transformateurs/{transformer}', [TransformerController::class, 'show'])->name('transformers.show');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductAdminController::class);
    Route::resource('environmental-footprints', \App\Http\Controllers\Admin\EnvironmentalFootprintController::class);
    Route::resource('environmental-claims', \App\Http\Controllers\Admin\EnvironmentalClaimController::class);
    Route::resource('compliance-checks', \App\Http\Controllers\Admin\ComplianceCheckController::class);
    Route::resource('producers', \App\Http\Controllers\Admin\ProducerController::class);
    Route::resource('transformers', \App\Http\Controllers\Admin\TransformerController::class);
    Route::resource('distributors', \App\Http\Controllers\Admin\DistributorController::class);
    Route::resource('supply-chain-traces', \App\Http\Controllers\Admin\SupplyChainTraceController::class);
    Route::resource('certificates', \App\Http\Controllers\Admin\CertificateController::class);
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class);
    Route::resource('alerts', \App\Http\Controllers\Admin\AlertController::class);
    Route::resource('ai-analyses', \App\Http\Controllers\Admin\AIAnalysisController::class);
    Route::resource('consumers', \App\Http\Controllers\Admin\ConsumerController::class);
    Route::resource('personal-ratings', \App\Http\Controllers\Admin\PersonalRatingController::class);
});

require __DIR__.'/auth.php';
