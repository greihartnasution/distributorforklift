<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomepageTestimonialRequest;
use App\Models\HomepageTestimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
                'image'      => $t->image ? '/storage/' . $t->image : null,
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

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        }

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
                'image'      => $testimonial->image ? '/storage/' . $testimonial->image : null,
                'sort_order' => $testimonial->sort_order,
                'is_active'  => $testimonial->is_active,
            ],
        ]);
    }

    public function update(HomepageTestimonialRequest $request, HomepageTestimonial $testimonial): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        } else {
            unset($data['image']);
        }

        $testimonial->update($data);

        return redirect()->route('admin.homepage.testimonials.index')
            ->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(HomepageTestimonial $testimonial): RedirectResponse
    {
        if ($testimonial->image) {
            Storage::disk('public')->delete($testimonial->image);
        }

        $testimonial->delete();

        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}
