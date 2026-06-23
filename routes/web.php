<?php

use App\Http\Controllers\Admin\HeroSliderController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\HomepageAboutController;
use App\Http\Controllers\Admin\HomepageClientController;
use App\Http\Controllers\Admin\HomepageShowcaseController;
use App\Http\Controllers\Admin\HomepageTestimonialController;
use App\Http\Controllers\Admin\ContactPageController as AdminContactPageController;
use App\Http\Controllers\Admin\InquirySettingController;
use App\Http\Controllers\Admin\NavItemController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Public\ContactPageController;
use App\Http\Controllers\Public\ProductController as PublicProductController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\ProfileController;
use App\Support\MediaUrl;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', function () {
    $sliders = \App\Models\HeroSlider::where('is_active', true)
        ->orderBy('order')
        ->get()
        ->map(fn ($s) => [
            ...$s->toArray(),
            'image' => MediaUrl::resolve($s->image),
        ]);

    $about = \App\Models\HomepageAbout::first();
    if ($about) {
        $about->image = MediaUrl::resolve($about->image);
    }

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
            'image'    => MediaUrl::resolve($n->image),
        ]);

    $testimonials = \App\Models\HomepageTestimonial::where('is_active', true)
        ->orderBy('sort_order')->orderBy('id')
        ->get()
        ->map(fn ($t) => [
            'name'     => $t->name,
            'position' => $t->position,
            'company'  => $t->company,
            'quote'    => $t->quote,
            'image'    => MediaUrl::resolve($t->image),
        ]);

    $clients = \App\Models\HomepageClient::where('is_active', true)
        ->orderBy('sort_order')->orderBy('id')
        ->get()
        ->map(fn ($c) => [
            'name'    => $c->name,
            'logo'    => MediaUrl::resolve($c->logo),
            'website' => $c->website,
        ]);

    $setting   = \App\Models\SiteSetting::first();
    $showcases = \App\Models\HomepageShowcase::all()->keyBy(fn ($s) => "{$s->row}_{$s->position}");
    $slot = fn ($r, $p) => [
        'title'       => $showcases->get("{$r}_{$p}")?->title,
        'description' => $showcases->get("{$r}_{$p}")?->description,
        'image'       => MediaUrl::resolve($showcases->get("{$r}_{$p}")?->image),
        'href'        => $showcases->get("{$r}_{$p}")?->href,
    ];

    return Inertia::render('Home', [
        'sliders'      => $sliders,
        'about'        => $about,
        'news'         => $news,
        'testimonials' => $testimonials,
        'clients'      => $clients,
        'showcase' => [
            'heading' => $setting?->showcase_heading ?? 'Distributor Forklift terdepan dalam solusi material handling industri di Indonesia',
            'row1'    => [$slot(1,1), $slot(1,2), $slot(1,3)],
            'row2'    => [$slot(2,1), $slot(2,2), $slot(2,3)],
            'row3'    => [$slot(3,1), $slot(3,2), $slot(3,3)],
        ],
    ]);
})->name('home');

// /produk redirect ke /forklift sebagai default
Route::redirect('/produk', '/forklift', 301)->name('products.index');

Route::get('/kontak', [ContactPageController::class, 'show'])->name('contact.show');
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');

// Static page preview (dummy data)
Route::get('/preview-halaman-statis', function () {
    return Inertia::render('Page/Show');
})->name('page.preview');

// Dynamic static pages
Route::get('/halaman/{slug}', [PageController::class, 'show'])->name('page.show');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard', [
            'stats' => [
                'pages'         => \App\Models\Page::where('is_published', true)->count(),
                'news'          => \App\Models\News::where('is_published', true)->count(),
                'sliders'       => \App\Models\HeroSlider::count(),
                'productsTotal' => \App\Models\Product::where('is_active', true)->count(),
                'productsForklift' => \App\Models\Product::where('is_active', true)
                    ->whereHas('category.parent', fn ($q) => $q->where('slug', 'forklift'))
                    ->count(),
                'productsSewa' => \App\Models\Product::where('is_active', true)
                    ->whereHas('category.parent', fn ($q) => $q->where('slug', 'sewa-forklift'))
                    ->count(),
            ],
        ]);
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
    Route::get('homepage/showcase', [HomepageShowcaseController::class, 'edit'])->name('admin.homepage.showcase');
    Route::put('homepage/showcase', [HomepageShowcaseController::class, 'update'])->name('admin.homepage.showcase.update');
    Route::resource('homepage/testimonials', HomepageTestimonialController::class)
        ->names('admin.homepage.testimonials')
        ->except(['show'])
        ->parameters(['testimonials' => 'testimonial']);
    Route::resource('homepage/clients', HomepageClientController::class)
        ->names('admin.homepage.clients')
        ->except(['show'])
        ->parameters(['clients' => 'client']);

    // Products CRUD
    Route::resource('product-categories', ProductCategoryController::class)
        ->names('admin.product-categories')
        ->except(['show'])
        ->parameters(['product-categories' => 'category']);

    Route::resource('products', ProductController::class)
        ->names('admin.products')
        ->except(['show']);

    // News CRUD
    Route::resource('news', AdminNewsController::class)
        ->names('admin.news')
        ->except(['show']);

    // Pages CRUD
    Route::resource('pages', AdminPageController::class)
        ->names('admin.pages')
        ->except(['show']);

    // Contact page (singleton)
    Route::get('contact', [AdminContactPageController::class, 'edit'])->name('admin.contact.edit');
    Route::put('contact', [AdminContactPageController::class, 'update'])->name('admin.contact.update');

    // Site settings
    Route::get('settings', [SiteSettingController::class, 'edit'])->name('admin.settings.edit');
    Route::put('settings', [SiteSettingController::class, 'update'])->name('admin.settings.update');

    // Inquiry section settings
    Route::get('inquiry', [InquirySettingController::class, 'edit'])->name('admin.inquiry.edit');
    Route::put('inquiry', [InquirySettingController::class, 'update'])->name('admin.inquiry.update');

    // Navigation editor (hidden — not in sidebar)
    Route::get('navigation', [NavItemController::class, 'index'])->name('admin.navigation.index');
    Route::post('navigation', [NavItemController::class, 'store'])->name('admin.navigation.store');
    Route::put('navigation/{navItem}', [NavItemController::class, 'update'])->name('admin.navigation.update');
    Route::delete('navigation/{navItem}', [NavItemController::class, 'destroy'])->name('admin.navigation.destroy');
    Route::post('navigation/{navItem}/move', [NavItemController::class, 'move'])->name('admin.navigation.move');
});

// Auth routes harus didaftarkan SEBELUM catch-all product routes
require __DIR__.'/auth.php';

// ── Product routes (catch-all — harus PALING AKHIR) ──
Route::get('/{systemCategory}', [PublicProductController::class, 'systemCategory'])->name('products.system');
Route::get('/{systemCategory}/{subCategory}', [PublicProductController::class, 'subCategory'])->name('products.sub');
Route::get('/{systemCategory}/{subCategory}/{product}', [PublicProductController::class, 'show'])->name('products.show');
