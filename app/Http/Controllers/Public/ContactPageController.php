<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactPage;
use Inertia\Inertia;
use Inertia\Response;

class ContactPageController extends Controller
{
    public function show(): Response
    {
        $contact = ContactPage::first();

        $page = $contact ? [
            'heading'       => $contact->heading,
            'description'   => $contact->description,
            'image'         => $contact->image ? '/storage/' . $contact->image : null,
            'content_left'  => $contact->content_left,
            'content_right' => $contact->content_right,
            'show_inquiry'  => $contact->show_inquiry,
        ] : [];

        return Inertia::render('Contact/Show', ['page' => $page]);
    }
}

