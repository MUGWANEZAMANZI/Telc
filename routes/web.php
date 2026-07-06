<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::view('pricing', 'pricing')->name('pricing');
Route::get('free-exam', \App\Livewire\GuestExam::class)->name('free.exam');
Route::view('premium-exam', 'premium-exam')->name('premium.exam');

Route::get('books', [\App\Http\Controllers\BookController::class, 'index'])->name('books.index');
Route::get('books/{book}/read', \App\Livewire\BookReader::class)->name('books.read');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('voice-practice', 'voice-practice')->name('voice.practice');
    Route::view('admin-guide', 'admin-guide')->name('admin.guide');
    Route::view('user-guide', 'user-guide')->name('user.guide');
    Route::get('billing', \App\Livewire\BillingPortal::class)->name('billing');
    Route::get('student-performance', \App\Livewire\StudentPerformance::class)->name('student.performance');
    Route::get('admin/finance', \App\Livewire\AdminFinance::class)->name('admin.finance');
    Route::get('owner/panel', \App\Livewire\OwnerDashboard::class)->name('owner.panel');
    Route::get('admin/books', \App\Livewire\AdminBooks::class)->name('admin.books');
});

Route::middleware(['auth'])->group(function () {
    // PayPal
    Route::get('paypal/exam-checkout/{examTemplate}', [\App\Http\Controllers\PayPalCheckoutController::class, 'examCheckout'])->name('paypal.exam.checkout');
    Route::get('paypal/plan-checkout', [\App\Http\Controllers\PayPalCheckoutController::class, 'planCheckout'])->name('paypal.plan.checkout');
    Route::get('paypal/success', [\App\Http\Controllers\PayPalCheckoutController::class, 'success'])->name('paypal.success');
    Route::get('paypal/cancel', [\App\Http\Controllers\PayPalCheckoutController::class, 'cancel'])->name('paypal.cancel');

    // Revolut
    Route::post('revolut/create-order', [\App\Http\Controllers\RevolutCheckoutController::class, 'createOrder'])->name('revolut.create');
    Route::get('revolut/success', [\App\Http\Controllers\RevolutCheckoutController::class, 'success'])->name('revolut.success');
    Route::get('revolut/cancel', [\App\Http\Controllers\RevolutCheckoutController::class, 'cancel'])->name('revolut.cancel');
});

Route::post('paypal/webhook', [\App\Http\Controllers\PayPalCheckoutController::class, 'webhook']);
Route::post('api/webhooks/revolut', [\App\Http\Controllers\RevolutCheckoutController::class, 'webhook'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/telc-assets/{file}', function (string $file) {
    abort_unless(str_ends_with($file, '.mp3'), 404);

    $path = base_path('instructions/'.basename($file));

    abort_unless(file_exists($path), 404);

    return response()->file($path, ['Content-Type' => 'audio/mpeg']);
})->where('file', '.*')->name('telc.assets');

Route::get('/exam-audio/{path}', function (string $path) {
    $disk = \Illuminate\Support\Facades\Storage::disk('exam-audio');

    abort_unless($disk->exists($path), 404);

    $mime = $disk->mimeType($path) ?? 'audio/mpeg';

    return response()->file($disk->path($path), ['Content-Type' => $mime]);
})->where('path', '.*')->name('exam.audio')->middleware(['auth', 'verified']);

require __DIR__.'/settings.php';
