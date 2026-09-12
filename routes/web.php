<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\PayPalCheckoutController;
use App\Http\Controllers\PaypackCheckoutController;
use App\Livewire\AdminBlog;
use App\Livewire\AdminBooks;
use App\Livewire\AdminEmailScheduler;
use App\Livewire\AdminFinance;
use App\Livewire\BillingPortal;
use App\Livewire\Blog;
use App\Livewire\BlogPostView;
use App\Livewire\BookReader;
use App\Livewire\GuestExam;
use App\Livewire\OwnerDashboard;
use App\Livewire\StudentPerformance;
use App\Livewire\SurveyPage;
use App\Models\BlogPost;
use App\Models\Book;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::view('/', 'welcome')->name('home');

Route::view('pricing', 'pricing')->name('pricing');
Route::view('terms', 'terms')->name('terms');
Route::view('privacy', 'privacy')->name('privacy');
Route::view('impressum', 'impressum')->name('impressum');
Route::get('free-exam/{templateId?}', GuestExam::class)->name('free.exam');
Route::view('premium-exam', 'premium-exam')->name('premium.exam');

Route::get('blog', Blog::class)->name('blog');
Route::get('blog/{slug}', BlogPostView::class)->name('blog.show');

Route::get('books', [BookController::class, 'index'])->name('books.index');
Route::get('books/{book}/read', BookReader::class)->name('books.read');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('admin-guide', 'admin-guide')->name('admin.guide');
    Route::view('user-guide', 'user-guide')->name('user.guide');
    Route::get('billing', BillingPortal::class)->name('billing');
    Route::get('student-performance', StudentPerformance::class)->name('student.performance');
    Route::get('admin/finance', AdminFinance::class)->name('admin.finance');
    Route::get('owner/panel', OwnerDashboard::class)->name('owner.panel');
    Route::get('admin/books', AdminBooks::class)->name('admin.books');
    Route::get('admin/blog', AdminBlog::class)->name('admin.blog');
    Route::get('admin/email-scheduler', AdminEmailScheduler::class)->name('admin.email-scheduler');
});

Route::get('voice-practice', function () {
    if (auth()->check()) {
        return view('voice-practice');
    }

    return view('guest-voice-practice');
})->name('voice.practice');

Route::get('survey', SurveyPage::class)->name('survey');

Route::get('sitemap.xml', function () {
    $posts = BlogPost::query()
        ->published()
        ->select(['slug', 'updated_at'])
        ->get();

    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

    $urls = [
        ['loc' => url('/'), 'priority' => '1.0'],
        ['loc' => url('/pricing'), 'priority' => '0.8'],
        ['loc' => url('/free-exam'), 'priority' => '0.9'],
        ['loc' => url('/voice-practice'), 'priority' => '0.7'],
        ['loc' => url('/blog'), 'priority' => '0.8'],
        ['loc' => url('/survey'), 'priority' => '0.5'],
    ];

    foreach ($urls as $url) {
        $xml .= '  <url>'."\n";
        $xml .= '    <loc>'.$url['loc'].'</loc>'."\n";
        $xml .= '    <changefreq>weekly</changefreq>'."\n";
        $xml .= '    <priority>'.$url['priority'].'</priority>'."\n";
        $xml .= '  </url>'."\n";
    }

    foreach ($posts as $post) {
        $xml .= '  <url>'."\n";
        $xml .= '    <loc>'.url('/blog/'.$post->slug).'</loc>'."\n";
        $xml .= '    <lastmod>'.$post->updated_at->format('Y-m-d').'</lastmod>'."\n";
        $xml .= '    <changefreq>monthly</changefreq>'."\n";
        $xml .= '    <priority>0.6</priority>'."\n";
        $xml .= '  </url>'."\n";
    }

    $xml .= '</urlset>';

    return response($xml, 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');

Route::middleware(['auth'])->group(function () {
    // PayPal
    Route::get('paypal/exam-checkout/{examTemplate}', [PayPalCheckoutController::class, 'examCheckout'])->name('paypal.exam.checkout');
    Route::get('paypal/plan-checkout', [PayPalCheckoutController::class, 'planCheckout'])->name('paypal.plan.checkout');
    Route::get('paypal/success', [PayPalCheckoutController::class, 'success'])->name('paypal.success');
    Route::get('paypal/cancel', [PayPalCheckoutController::class, 'cancel'])->name('paypal.cancel');

    // Paypack
    Route::post('paypack/cashin', [PaypackCheckoutController::class, 'processCashin'])->name('paypack.cashin');
    Route::get('paypack/status/{ref}', [PaypackCheckoutController::class, 'checkStatus'])->name('paypack.status');
});

Route::post('paypal/webhook', [PayPalCheckoutController::class, 'webhook']);
Route::post('api/webhooks/paypack', [PaypackCheckoutController::class, 'webhook']);

Route::get('/goethe-assets/{file}', function (string $file) {
    abort_unless(str_ends_with($file, '.mp3'), 404);

    $path = base_path('instructions/'.basename($file));

    abort_unless(file_exists($path), 404);

    return response()->file($path, ['Content-Type' => 'audio/mpeg']);
})->where('file', '.*')->name('goethe.assets');

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
