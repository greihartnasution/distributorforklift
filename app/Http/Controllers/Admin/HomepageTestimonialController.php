<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomepageTestimonialRequest;
use App\Models\HomepageTestimonial;
use App\Support\MediaUrl;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class HomepageTestimonialController extends Controller
{
    public function index(): Response
    {
        $testimonials = HomepageTestimonial::orderBy('sort_order')->orderBy('id')->get();

        return Inertia::render('Admin/Homepage/Testimonial/Index', [
            'testimonials' => $testimonials->map(fn ($t) => [
                'id'         => $t->id,
                'name'       => $t->name,
                'position'   => $t->position,
                'company'    => $t->company,
                'quote'      => $t->quote,
                'image'      => MediaUrl::resolve($t->image),
                'sort_order' => $t->sort_order,
                'is_active'  => $t->is_active,
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Homepage/Testimonial/Form', [
            'testimonial' => null,
        ]);
    }

    public function store(HomepageTestimonialRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('testimonials', 'public');
        }
        unset($data['image_file'], $data['clear_image']);

        HomepageTestimonial::create($data);

        return redirect()->route('admin.homepage.testimonials.index')
            ->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(HomepageTestimonial $testimonial): Response
    {
        return Inertia::render('Admin/Homepage/Testimonial/Form', [
            'testimonial' => [
                'id'         => $testimonial->id,
                'name'       => $testimonial->name,
                'position'   => $testimonial->position,
                'company'    => $testimonial->company,
                'quote'      => $testimonial->quote,
                'image'      => MediaUrl::resolve($testimonial->image),
                'sort_order' => $testimonial->sort_order,
                'is_active'  => $testimonial->is_active,
            ],
        ]);
    }

    public function update(HomepageTestimonialRequest $request, HomepageTestimonial $testimonial): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image_file')) {
            MediaUrl::deleteIfLocal($testimonial->image);
            $data['image'] = $request->file('image_file')->store('testimonials', 'public');
        } elseif ($request->boolean('clear_image')) {
            MediaUrl::deleteIfLocal($testimonial->image);
            $data['image'] = null;
        } elseif (!empty($data['image'])) {
            // URL mode — value already in $data
        } else {
            unset($data['image']); // upload mode, no new file → keep existing
        }
        unset($data['image_file'], $data['clear_image']);

        $testimonial->update($data);

        return redirect()->route('admin.homepage.testimonials.index')
            ->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(HomepageTestimonial $testimonial): RedirectResponse
    {
        MediaUrl::deleteIfLocal($testimonial->image);

        $testimonial->delete();

        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}
