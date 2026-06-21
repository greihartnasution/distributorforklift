<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    private function mapProduct(Product $p, string $systemSlug): array
    {
        return [
            'id'               => $p->id,
            'name'             => $p->name,
            'slug'             => $p->slug,
            'short_description'=> $p->short_description,
            'specs'            => $p->specs ?? [],
            'image'            => $p->image ? '/storage/' . $p->image : null,
            'category_slug'    => $p->category?->slug,
            'system_slug'      => $systemSlug,
        ];
    }

    private function mapHero(ProductCategory $cat, ?ProductCategory $system = null): array
    {
        // Sub-category pages: label auto from system category name
        // System category pages: label from nav_label field
        $label = $system
            ? $system->name
            : ($cat->nav_label ?: 'Katalog Produk');

        return [
            'label'        => $label,
            'name'         => $cat->name,
            'description'  => $cat->description,
            'image'        => $cat->image ? '/storage/' . $cat->image : null,
            'show_inquiry' => $cat->show_inquiry,
        ];
    }

    public function systemCategory(string $systemSlug): Response
    {
        $system = ProductCategory::where('slug', $systemSlug)
            ->where('is_system', true)
            ->where('is_active', true)
            ->firstOrFail();

        $subCategories = ProductCategory::where('parent_id', $system->id)
            ->where('is_active', true)
            ->orderBy('sort_order')->orderBy('id')
            ->get();

        $subIds = $subCategories->pluck('id');

        $products = Product::with('category')
            ->whereIn('product_category_id', $subIds)
            ->where('is_active', true)
            ->get()
            ->map(fn ($p) => $this->mapProduct($p, $system->slug));

        return Inertia::render('Products/Index', [
            'systemCategory' => ['slug' => $system->slug, 'name' => $system->name],
            'categories'     => $subCategories->map(fn ($c) => [
                'id'   => $c->id, 'name' => $c->name, 'slug' => $c->slug,
            ]),
            'products'       => $products,
            'activeCategory' => null,
            'hero'           => $this->mapHero($system),
        ]);
    }

    public function subCategory(string $systemSlug, string $subSlug): Response
    {
        $system = ProductCategory::where('slug', $systemSlug)
            ->where('is_system', true)
            ->where('is_active', true)
            ->firstOrFail();

        $sub = ProductCategory::where('slug', $subSlug)
            ->where('parent_id', $system->id)
            ->where('is_active', true)
            ->firstOrFail();

        $allSubs = ProductCategory::where('parent_id', $system->id)
            ->where('is_active', true)
            ->orderBy('sort_order')->orderBy('id')
            ->get();

        $products = Product::with('category')
            ->where('product_category_id', $sub->id)
            ->where('is_active', true)
            ->get()
            ->map(fn ($p) => $this->mapProduct($p, $system->slug));

        return Inertia::render('Products/Index', [
            'systemCategory' => ['slug' => $system->slug, 'name' => $system->name],
            'categories'     => $allSubs->map(fn ($c) => [
                'id' => $c->id, 'name' => $c->name, 'slug' => $c->slug,
            ]),
            'products'       => $products,
            'activeCategory' => [
                'id'           => $sub->id,
                'name'         => $sub->name,
                'slug'         => $sub->slug,
                'show_inquiry' => $sub->show_inquiry,
            ],
            'hero'           => $this->mapHero($sub, $system),
        ]);
    }

    public function show(string $systemSlug, string $subSlug, string $productSlug): Response
    {
        $system = ProductCategory::where('slug', $systemSlug)
            ->where('is_system', true)
            ->where('is_active', true)
            ->firstOrFail();

        $sub = ProductCategory::where('slug', $subSlug)
            ->where('parent_id', $system->id)
            ->where('is_active', true)
            ->firstOrFail();

        $product = Product::where('slug', $productSlug)
            ->where('product_category_id', $sub->id)
            ->where('is_active', true)
            ->firstOrFail();

        return Inertia::render('Products/Show', [
            'systemCategory' => ['slug' => $system->slug, 'name' => $system->name],
            'subCategory'    => ['slug' => $sub->slug,    'name' => $sub->name],
            'product'        => [
                'id'               => $product->id,
                'name'             => $product->name,
                'short_description'=> $product->short_description,
                'full_description' => $product->full_description,
                'specs'            => $product->specs ?? [],
                'highlights'       => $product->highlights ?? [],
                'highlight_image'  => $product->highlight_image,
                'details'          => $product->details ?? [],
                'solutions_title'      => $product->solutions_title,
                'solutions_description' => $product->solutions_description,
                'solutions'        => $product->solutions ?? [],
                'media_items'      => $product->media_items ?? [],
                'model_overview'   => $product->model_overview ?? [],
                'downloads'        => $product->downloads ?? [],
                'image'            => $product->image ? '/storage/' . $product->image : null,
                'image_url'        => $product->image_url,
                'hero_cert_badge_1' => $product->hero_cert_badge_1,
                'hero_cert_badge_2' => $product->hero_cert_badge_2,
                'video_url'        => $product->video_url,
            ],
        ]);
    }
}
