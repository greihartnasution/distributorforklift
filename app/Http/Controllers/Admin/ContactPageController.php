<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactPageRequest;
use App\Models\ContactPage;
use App\Support\MediaUrl;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactPageController extends Controller
{
    public function edit(): Response
    {
        $contact = ContactPage::firstOrCreate([], [
            'heading'      => 'Kami siap membantu Anda.',
            'show_inquiry' => true,
        ]);

        return Inertia::render('Admin/ContactPage/Edit', [
            'contact' => [
                'id'            => $contact->id,
                'heading'       => $contact->heading,
                'description'   => $contact->description,
                'image'         => MediaUrl::resolve($contact->image),
                'content_left'  => $contact->content_left,
                'content_right' => $contact->content_right,
                'show_inquiry'  => $contact->show_inquiry,
            ],
        ]);
    }

    public function update(ContactPageRequest $request): RedirectResponse
    {
        $contact = ContactPage::firstOrCreate([]);
        $data    = $request->validated();

        $data['show_inquiry'] = $request->boolean('show_inquiry');

        if ($request->hasFile('image_file')) {
            MediaUrl::deleteIfLocal($contact->image);
            $data['image'] = $request->file('image_file')->store('contact', 'public');
        } elseif ($request->boolean('clear_image')) {
            MediaUrl::deleteIfLocal($contact->image);
            $data['image'] = null;
        } elseif (!empty($data['image'])) {
            // URL mode — value already in $data
        } else {
            unset($data['image']); // upload mode, no new file → keep existing
        }
        unset($data['image_file'], $data['clear_image']);

        $contact->update($data);

        return back()->with('success', 'Halaman Kontak berhasil diperbarui.');
    }
}

