<?php

use App\Http\Controllers\Admin\HeroSliderController;
use App\Http\Controllers\Admin\HomepageAboutController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', function () {
    $sliders = \App\Models\HeroSlider::where('is_active', true)
        ->orderBy('order')
        ->get();

    $about = \App\Models\HomepageAbout::first();

    $news = \App\Models\News::where('is_published', true)
        ->orderByDesc('published_at')
        ->limit(3)
        ->get()
        ->map(fn ($n) => [
            'slug'     => $n->slug,
            'category' => $n->category,
            'date'     => $n->formatted_date,
            'title'    => $n->title,
            'excerpt'  => $n->excerpt,
            'image'    => $n->image ? '/storage/' . $n->image : null,
        ]);

    return Inertia::render('Home', [
        'sliders' => $sliders,
        'about'   => $about,
        'news'    => $news,
    ]);
})->name('home');

Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Company Profile Management
    Route::resource('sliders', HeroSliderController::class)
        ->names('admin.sliders')
        ->except(['show']);

    // Homepage sections
    Route::get('homepage/tentang', [HomepageAboutController::class, 'edit'])->name('admin.homepage.tentang');
    Route::put('homepage/tentang', [HomepageAboutController::class, 'update'])->name('admin.homepage.tentang.update');

    // News CRUD
    Route::resource('news', AdminNewsController::class)
        ->names('admin.news')
        ->except(['show']);
});

require __DIR__.'/auth.php';
