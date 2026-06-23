<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Support\MediaUrl;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('is_published', true)
            ->orderByDesc('published_at')
            ->paginate(9)
            ->through(fn ($item) => [
                'id'       => $item->id,
                'title'    => $item->title,
                'slug'     => $item->slug,
                'category' => $item->category,
                'excerpt'  => $item->excerpt,
                'image'    => MediaUrl::resolve($item->image),
                'date'     => $item->formatted_date,
            ]);

        return Inertia::render('News/Index', [
            'news' => $news,
        ]);
    }

    public function show(string $slug)
    {
        $article = News::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return Inertia::render('News/Show', [
            'article' => [
                'id'       => $article->id,
                'title'    => $article->title,
                'category' => $article->category,
                'content'  => $article->content,
                'excerpt'  => $article->excerpt,
                'image'    => MediaUrl::resolve($article->image),
                'date'     => $article->formatted_date,
            ],
        ]);
    }
}
