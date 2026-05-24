<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsRequest;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function index(): Response
    {
        $news = News::latest('published_at')
            ->latest('id')
            ->paginate(15)
            ->through(fn ($n) => [
                'id'           => $n->id,
                'title'        => $n->title,
                'slug'         => $n->slug,
                'category'     => $n->category,
                'is_published' => $n->is_published,
                'published_at' => $n->published_at?->format('d M Y'),
                'image'        => $n->image ? '/storage/' . $n->image : null,
            ]);

        return Inertia::render('Admin/News/Index', [
            'news' => $news,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/News/Create');
    }

    public function store(NewsRequest $request): RedirectResponse
    {
        $data                = $request->validated();
        $data['is_published'] = $request->boolean('is_published');
        $data['slug']        = $data['slug'] ?: Str::slug($data['title']);

        if (empty($data['published_at'])) {
            $data['published_at'] = $data['is_published'] ? now() : null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        News::create($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(News $news): Response
    {
        return Inertia::render('Admin/News/Edit', [
            'news' => [
                'id'           => $news->id,
                'title'        => $news->title,
                'slug'         => $news->slug,
                'category'     => $news->category,
                'excerpt'      => $news->excerpt,
                'content'      => $news->content,
                'image'        => $news->image ? '/storage/' . $news->image : null,
                'is_published' => $news->is_published,
                'published_at' => $news->published_at?->format('Y-m-d'),
            ],
        ]);
    }

    public function update(NewsRequest $request, News $news): RedirectResponse
    {
        $data                = $request->validated();
        $data['is_published'] = $request->boolean('is_published');
        $data['slug']        = $data['slug'] ?: Str::slug($data['title']);

        if (empty($data['published_at']) && $data['is_published'] && ! $news->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        } else {
            unset($data['image']);
        }

        $news->update($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(News $news): RedirectResponse
    {
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }
}
