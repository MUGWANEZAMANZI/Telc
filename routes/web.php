<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::view('pricing', 'pricing')->name('pricing');
Route::get('free-exam', \App\Livewire\GuestExam::class)->name('free.exam');
Route::view('premium-exam', 'premium-exam')->name('premium.exam');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('voice-practice', 'voice-practice')->name('voice.practice');
    Route::view('admin-guide', 'admin-guide')->name('admin.guide');
    Route::view('user-guide', 'user-guide')->name('user.guide');
    Route::get('billing', \App\Livewire\BillingPortal::class)->name('billing');
    Route::get('student-performance', \App\Livewire\StudentPerformance::class)->name('student.performance');
});

Route::post('stripe/webhook', [\App\Http\Controllers\CheckoutController::class, 'webhook'])->name('stripe.webhook');

Route::middleware(['auth'])->group(function () {
    Route::get('checkout', [\App\Http\Controllers\CheckoutController::class, 'checkout'])->name('checkout');
});

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
