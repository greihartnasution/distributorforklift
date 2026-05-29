<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InquirySettingRequest;
use App\Models\InquirySetting;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class InquirySettingController extends Controller
{
    public function edit(): Response
    {
        $inquiry = InquirySetting::firstOrCreate([], [
            'heading' => 'Kami siap membantu Anda.',
            'intro'   => 'Apakah Anda memiliki pertanyaan atau membutuhkan saran personal? Tim ahli kami siap membantu Anda menemukan solusi yang tepat — secara individual dan cepat.',
            'hours'   => 'Senin hingga Jumat',
        ]);

        return Inertia::render('Admin/Inquiry/Edit', [
            'inquiry' => [
                'heading' => $inquiry->heading,
                'intro'   => $inquiry->intro,
                'hours'   => $inquiry->hours,
            ],
        ]);
    }

    public function update(InquirySettingRequest $request): RedirectResponse
    {
        $inquiry = InquirySetting::firstOrCreate([]);
        $inquiry->update($request->validated());

        return back()->with('success', 'Pengaturan Inquiry berhasil disimpan.');
    }
}
