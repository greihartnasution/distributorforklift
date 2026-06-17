<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InquirySettingRequest;
use App\Models\InquirySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
                'heading'            => $inquiry->heading,
                'intro'              => $inquiry->intro,
                'hours'              => $inquiry->hours,
                'consultant_name'    => $inquiry->consultant_name,
                'consultant_title'   => $inquiry->consultant_title,
                'consultant_company' => $inquiry->consultant_company,
                'consultant_phone'   => $inquiry->consultant_phone,
                'consultant_email'   => $inquiry->consultant_email,
                'consultant_photo'   => $inquiry->consultant_photo
                    ? '/storage/' . $inquiry->consultant_photo : null,
                'instagram'          => $inquiry->instagram,
                'tiktok'             => $inquiry->tiktok,
                'youtube'            => $inquiry->youtube,
                'facebook'           => $inquiry->facebook,
            ],
        ]);
    }

    public function update(InquirySettingRequest $request): RedirectResponse
    {
        $inquiry = InquirySetting::firstOrCreate([]);
        $data    = $request->validated();

        if ($request->hasFile('consultant_photo')) {
            if ($inquiry->consultant_photo) {
                Storage::disk('public')->delete($inquiry->consultant_photo);
            }
            $data['consultant_photo'] = $request->file('consultant_photo')
                ->store('inquiry', 'public');
        } else {
            unset($data['consultant_photo']);
        }

        if ($request->boolean('clear_photo')) {
            if ($inquiry->consultant_photo) {
                Storage::disk('public')->delete($inquiry->consultant_photo);
            }
            $data['consultant_photo'] = null;
        }

        unset($data['clear_photo']);
        $inquiry->update($data);

        return back()->with('success', 'Pengaturan Inquiry berhasil disimpan.');
    }
}
