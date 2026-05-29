<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactPageRequest;
use App\Models\ContactPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
                'image'         => $contact->image ? '/storage/' . $contact->image : null,
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

        if ($request->hasFile('image')) {
            if ($contact->image) {
                Storage::disk('public')->delete($contact->image);
            }
            $data['image'] = $request->file('image')->store('contact', 'public');
        } else {
            unset($data['image']);
        }

        $contact->update($data);

        return back()->with('success', 'Halaman Kontak berhasil diperbarui.');
    }
}

