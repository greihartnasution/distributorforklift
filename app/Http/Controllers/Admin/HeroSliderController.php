<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HeroSliderRequest;
use App\Models\HeroSlider;
use App\Support\MediaUrl;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class HeroSliderController extends Controller
{
    public function index(): Response
    {
        $sliders = HeroSlider::orderBy('order')->orderBy('id')->get()
            ->map(fn ($s) => [
                ...$s->toArray(),
                'image' => MediaUrl::resolve($s->image),
            ]);

        return Inertia::render('Admin/Slider/Index', [
            'sliders' => $sliders,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Slider/Create');
    }

    public function store(HeroSliderRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['show_cta'] = $request->boolean('show_cta');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('sliders', 'public');
        }
        unset($data['image_file'], $data['clear_image']);

        if (! $data['show_cta']) {
            $data['cta_text'] = null;
            $data['cta_url']  = null;
        }

        HeroSlider::create($data);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider berhasil ditambahkan.');
    }

    public function edit(HeroSlider $slider): Response
    {
        return Inertia::render('Admin/Slider/Edit', [
            'slider' => [
                ...$slider->toArray(),
                'image' => MediaUrl::resolve($slider->image),
            ],
        ]);
    }

    public function update(HeroSliderRequest $request, HeroSlider $slider): RedirectResponse
    {
        $data = $request->validated();
        $data['show_cta'] = $request->boolean('show_cta');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image_file')) {
            MediaUrl::deleteIfLocal($slider->image);
            $data['image'] = $request->file('image_file')->store('sliders', 'public');
        } elseif ($request->boolean('clear_image')) {
            MediaUrl::deleteIfLocal($slider->image);
            $data['image'] = null;
        } elseif (!empty($data['image'])) {
            // URL mode — value already in $data
        } else {
            unset($data['image']); // upload mode, no new file → keep existing
        }
        unset($data['image_file'], $data['clear_image']);

        if (! $data['show_cta']) {
            $data['cta_text'] = null;
            $data['cta_url']  = null;
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider berhasil diperbarui.');
    }

    public function destroy(HeroSlider $slider): RedirectResponse
    {
        MediaUrl::deleteIfLocal($slider->image);

        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider berhasil dihapus.');
    }
}
