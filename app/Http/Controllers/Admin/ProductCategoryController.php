<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductCategoryRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Support\MediaUrl;
use Inertia\Inertia;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $systemCategories = ProductCategory::where('is_system', true)
            ->with(['children' => fn ($q) => $q->withCount('products')->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')->orderBy('id')
            ->get()
            ->map(function ($sys) {
                $sys->products_count = $sys->children->sum('products_count');
                return $sys;
            });

        return Inertia::render('Admin/Products/Category/Index', [
            'systemCategories' => $systemCategories,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Category/Form', [
            'systemCategories' => ProductCategory::where('is_system', true)
                ->orderBy('sort_order')->get(['id', 'name', 'slug']),
        ]);
    }

    public function store(ProductCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('product-categories', 'public');
        }
        unset($data['image_file'], $data['clear_image']);

        ProductCategory::create($data);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Kategori produk berhasil ditambahkan.');
    }

    public function edit(ProductCategory $category)
    {
        return Inertia::render('Admin/Products/Category/Form', [
            'category'         => $category,
            'systemCategories' => ProductCategory::where('is_system', true)
                ->orderBy('sort_order')->get(['id', 'name', 'slug']),
        ]);
    }

    public function update(ProductCategoryRequest $request, ProductCategory $category)
    {
        $data = $request->validated();

        if ($request->hasFile('image_file')) {
            MediaUrl::deleteIfLocal($category->image);
            $data['image'] = $request->file('image_file')->store('product-categories', 'public');
        } elseif ($request->boolean('clear_image')) {
            MediaUrl::deleteIfLocal($category->image);
            $data['image'] = null;
        } elseif (!empty($data['image'])) {
            // URL mode — value already in $data
        } else {
            unset($data['image']); // upload mode, no new file → keep existing
        }
        unset($data['image_file'], $data['clear_image']);

        $category->update($data);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Kategori produk berhasil diperbarui.');
    }

    public function destroy(ProductCategory $category)
    {
        if ($category->is_system) {
            return redirect()->back()
                ->with('error', 'Kategori sistem tidak dapat dihapus.');
        }

        if ($category->products()->exists()) {
            return redirect()->back()
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        MediaUrl::deleteIfLocal($category->image);
        $category->delete();

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Kategori produk berhasil dihapus.');
    }
}
