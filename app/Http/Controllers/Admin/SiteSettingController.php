<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteSettingRequest;
use App\Models\SiteSetting;
use App\Support\MediaUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SiteSettingController extends Controller
{
    public function edit(): Response
    {
        $setting = SiteSetting::firstOrCreate([]);

        return Inertia::render('Admin/Settings/Edit', [
            'setting' => [
                'site_name'        => $setting->site_name,
                'meta_description' => $setting->meta_description,
                'og_image_url'     => MediaUrl::resolve($setting->og_image),
                'favicon_url'      => $setting->favicon ? '/storage/' . $setting->favicon : null,
                'phone'            => $setting->phone,
                'whatsapp'         => $setting->whatsapp,
                'email'            => $setting->email,
                'address'          => $setting->address,
                'youtube'          => $setting->youtube,
                'instagram'        => $setting->instagram,
                'tiktok'           => $setting->tiktok,
                'facebook'         => $setting->facebook,
            ],
        ]);
    }

    public function update(SiteSettingRequest $request): RedirectResponse
    {
        $setting = SiteSetting::firstOrCreate([]);
        $data    = $request->validated();

        if ($request->hasFile('og_image_file')) {
            MediaUrl::deleteIfLocal($setting->og_image);
            $data['og_image'] = $request->file('og_image_file')->store('seo', 'public');
        } elseif ($request->boolean('clear_og_image')) {
            MediaUrl::deleteIfLocal($setting->og_image);
            $data['og_image'] = null;
        } elseif (!empty($data['og_image'])) {
            // URL mode — value already in $data
        } else {
            unset($data['og_image']); // upload mode, no new file → keep existing
        }
        unset($data['og_image_file'], $data['clear_og_image']);

        if ($request->hasFile('favicon')) {
            if ($setting->favicon) Storage::disk('public')->delete($setting->favicon);
            $data['favicon'] = $request->file('favicon')->store('seo', 'public');
        } else {
            unset($data['favicon']);
        }

        if ($request->boolean('clear_favicon')) {
            if ($setting->favicon) Storage::disk('public')->delete($setting->favicon);
            $data['favicon'] = null;
        }

        $setting->update($data);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
