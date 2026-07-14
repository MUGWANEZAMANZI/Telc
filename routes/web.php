<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\PayPalCheckoutController;
use App\Http\Controllers\RevolutCheckoutController;
use App\Livewire\AdminBooks;
use App\Livewire\AdminFinance;
use App\Livewire\BillingPortal;
use App\Livewire\BookReader;
use App\Livewire\GuestExam;
use App\Livewire\OwnerDashboard;
use App\Livewire\StudentPerformance;
use App\Models\Book;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::view('/', 'welcome')->name('home');

Route::view('pricing', 'pricing')->name('pricing');
Route::get('free-exam/{templateId?}', GuestExam::class)->name('free.exam');
Route::view('premium-exam', 'premium-exam')->name('premium.exam');

Route::get('books', [BookController::class, 'index'])->name('books.index');
Route::get('books/{book}/read', BookReader::class)->name('books.read');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('voice-practice', 'voice-practice')->name('voice.practice');
    Route::view('admin-guide', 'admin-guide')->name('admin.guide');
    Route::view('user-guide', 'user-guide')->name('user.guide');
    Route::get('billing', BillingPortal::class)->name('billing');
    Route::get('student-performance', StudentPerformance::class)->name('student.performance');
    Route::get('admin/finance', AdminFinance::class)->name('admin.finance');
    Route::get('owner/panel', OwnerDashboard::class)->name('owner.panel');
    Route::get('admin/books', AdminBooks::class)->name('admin.books');
});

Route::middleware(['auth'])->group(function () {
    // PayPal
    Route::get('paypal/exam-checkout/{examTemplate}', [PayPalCheckoutController::class, 'examCheckout'])->name('paypal.exam.checkout');
    Route::get('paypal/plan-checkout', [PayPalCheckoutController::class, 'planCheckout'])->name('paypal.plan.checkout');
    Route::get('paypal/success', [PayPalCheckoutController::class, 'success'])->name('paypal.success');
    Route::get('paypal/cancel', [PayPalCheckoutController::class, 'cancel'])->name('paypal.cancel');

    // Revolut
    Route::post('revolut/create-order', [RevolutCheckoutController::class, 'createOrder'])->name('revolut.create');
    Route::get('revolut/success', [RevolutCheckoutController::class, 'success'])->name('revolut.success');
    Route::get('revolut/cancel', [RevolutCheckoutController::class, 'cancel'])->name('revolut.cancel');
});

Route::post('paypal/webhook', [PayPalCheckoutController::class, 'webhook']);
Route::post('api/webhooks/revolut', [RevolutCheckoutController::class, 'webhook']);

Route::get('/telc-assets/{file}', function (string $file) {
    abort_unless(str_ends_with($file, '.mp3'), 404);

    $path = base_path('instructions/'.basename($file));

    abort_unless(file_exists($path), 404);

    return response()->file($path, ['Content-Type' => 'audio/mpeg']);
})->where('file', '.*')->name('telc.assets');

Route::get('/exam-audio/{path}', function (string $path) {
    $disk = Storage::disk('exam-audio');

    abort_unless($disk->exists($path), 404);

    $mime = $disk->mimeType($path) ?: 'audio/mpeg';

    return response()->file($disk->path($path), ['Content-Type' => $mime]);
})->where('path', '.*')->name('exam.audio')->middleware(['auth', 'verified']);

Route::get('/books-pdf/{book}', function (Book $book) {
    abort_unless($book->is_published || (auth()->check() && auth()->user()->isAdmin()), 404);

    $path = storage_path('app/public/'.$book->pdf_path);
    if (! file_exists($path)) {
        $path = storage_path('app/'.$book->pdf_path);
    }

    abort_unless(file_exists($path), 404);

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.basename($book->pdf_path).'"',
    ]);
})->name('books.pdf');

Route::get('/books-cover/{book}', function (Book $book) {
    abort_unless((bool) $book->cover_image, 404);

    $path = storage_path('app/public/'.$book->cover_image);
    if (! file_exists($path)) {
        $path = storage_path('app/'.$book->cover_image);
    }

    abort_unless(file_exists($path), 404);

    $mime = mime_content_type($path) ?: 'image/jpeg';

    return response()->file($path, ['Content-Type' => $mime]);
})->name('books.cover');

require __DIR__.'/settings.php';
