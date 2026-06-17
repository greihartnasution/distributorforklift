<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Products/Product/Index', [
            'systemCategories' => ProductCategory::where('is_system', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug']),

            'products' => Product::with(['category.parent'])
                ->orderBy('product_category_id')
                ->orderBy('id')
                ->get()
                ->map(fn ($p) => [
                    'id'            => $p->id,
                    'name'          => $p->name,
                    'slug'          => $p->slug,
                    'image'         => $p->image ? '/storage/' . $p->image : null,
                    'is_active'     => $p->is_active,
                    'category_name' => $p->category?->name,
                    'category_slug' => $p->category?->slug,
                    'system_slug'   => $p->category?->parent?->slug,
                    'system_name'   => $p->category?->parent?->name,
                ]),
        ]);
    }

    private function systemCategoriesForForm(): \Illuminate\Support\Collection
    {
        return ProductCategory::where('is_system', true)
            ->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')->orderBy('id')
            ->get(['id', 'name', 'slug']);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Product/Form', [
            'systemCategories' => $this->systemCategoriesForForm(),
        ]);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        unset($data['clear_image'], $data['clear_highlight_image']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('highlight_image_file')) {
            $data['highlight_image'] = '/storage/' . $request->file('highlight_image_file')->store('highlights', 'public');
        }
        unset($data['highlight_image_file']);

        if ($request->hasFile('highlight_item_images') && !empty($data['highlights'])) {
            foreach ($request->file('highlight_item_images') as $index => $file) {
                if ($file && isset($data['highlights'][$index])) {
                    $data['highlights'][$index]['image_url'] = '/storage/' . $file->store('highlight-icons', 'public');
                }
            }
        }
        unset($data['highlight_item_images']);

        if ($request->hasFile('detail_icon_images') && !empty($data['details'])) {
            foreach ($request->file('detail_icon_images') as $index => $file) {
                if ($file && isset($data['details'][$index])) {
                    $data['details'][$index]['icon_image'] = '/storage/' . $file->store('detail-icons', 'public');
                }
            }
        }
        if ($request->hasFile('detail_body_images') && !empty($data['details'])) {
            foreach ($request->file('detail_body_images') as $index => $file) {
                if ($file && isset($data['details'][$index])) {
                    $data['details'][$index]['image'] = '/storage/' . $file->store('details', 'public');
                }
            }
        }
        unset($data['detail_icon_images'], $data['detail_body_images']);

        if ($request->hasFile('media_images') && !empty($data['media_items'])) {
            foreach ($request->file('media_images') as $index => $file) {
                if ($file && isset($data['media_items'][$index])) {
                    $data['media_items'][$index]['image'] = '/storage/' . $file->store('media', 'public');
                }
            }
        }
        unset($data['media_images']);

        if ($request->hasFile('download_files') && !empty($data['downloads'])) {
            foreach ($request->file('download_files') as $index => $file) {
                if ($file && isset($data['downloads'][$index])) {
                    $data['downloads'][$index]['file'] = '/storage/' . $file->store('downloads', 'public');
                    $data['downloads'][$index]['size'] = $this->formatFileSize($file->getSize());
                }
            }
        }
        unset($data['download_files']);

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $product->load('category.parent');

        return Inertia::render('Admin/Products/Product/Form', [
            'product'          => array_merge($product->toArray(), [
                'image_preview'      => $product->image ? '/storage/' . $product->image : null,
                'system_category_id' => $product->category?->parent?->id,
            ]),
            'systemCategories' => $this->systemCategoriesForForm(),
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        unset($data['clear_image']);

        // Main product image
        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->boolean('clear_image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = null;
        } else {
            unset($data['image']);
        }

        // Highlight section image
        if ($request->hasFile('highlight_image_file')) {
            $this->deleteHighlightImage($product->highlight_image);
            $data['highlight_image'] = '/storage/' . $request->file('highlight_image_file')->store('highlights', 'public');
        } elseif ($request->boolean('clear_highlight_image')) {
            $this->deleteHighlightImage($product->highlight_image);
            $data['highlight_image'] = null;
        } elseif (!empty($data['highlight_image'])) {
            // URL mode — value already in $data
        } else {
            unset($data['highlight_image']); // upload mode, no new file → keep existing
        }
        unset($data['highlight_image_file'], $data['clear_highlight_image']);

        // Per-item highlight images
        if ($request->hasFile('highlight_item_images') && !empty($data['highlights'])) {
            foreach ($request->file('highlight_item_images') as $index => $file) {
                if ($file && isset($data['highlights'][$index])) {
                    $data['highlights'][$index]['image_url'] = '/storage/' . $file->store('highlight-icons', 'public');
                }
            }
        }
        unset($data['highlight_item_images']);

        // Per-item detail images
        if ($request->hasFile('detail_icon_images') && !empty($data['details'])) {
            foreach ($request->file('detail_icon_images') as $index => $file) {
                if ($file && isset($data['details'][$index])) {
                    $data['details'][$index]['icon_image'] = '/storage/' . $file->store('detail-icons', 'public');
                }
            }
        }
        if ($request->hasFile('detail_body_images') && !empty($data['details'])) {
            foreach ($request->file('detail_body_images') as $index => $file) {
                if ($file && isset($data['details'][$index])) {
                    $data['details'][$index]['image'] = '/storage/' . $file->store('details', 'public');
                }
            }
        }
        unset($data['detail_icon_images'], $data['detail_body_images']);

        // Per-item media gallery images
        if ($request->hasFile('media_images') && !empty($data['media_items'])) {
            foreach ($request->file('media_images') as $index => $file) {
                if ($file && isset($data['media_items'][$index])) {
                    $data['media_items'][$index]['image'] = '/storage/' . $file->store('media', 'public');
                }
            }
        }
        unset($data['media_images']);

        // Per-item download files
        if ($request->hasFile('download_files') && !empty($data['downloads'])) {
            foreach ($request->file('download_files') as $index => $file) {
                if ($file && isset($data['downloads'][$index])) {
                    $data['downloads'][$index]['file'] = '/storage/' . $file->store('downloads', 'public');
                    $data['downloads'][$index]['size'] = $this->formatFileSize($file->getSize());
                }
            }
        }
        unset($data['download_files']);

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    private function deleteHighlightImage(?string $value): void
    {
        if ($value && !str_starts_with($value, 'http')) {
            $path = ltrim(str_replace('/storage/', '', $value), '/');
            Storage::disk('public')->delete($path);
        }
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }

        return round($bytes / 1024, 1) . ' KB';
    }

    public function destroy(Product $product)
    {
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
