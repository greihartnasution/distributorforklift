<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteSettingRequest;
use App\Models\SiteSetting;
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
                'og_image_url'     => $setting->og_image ? '/storage/' . $setting->og_image : null,
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

        foreach (['og_image', 'favicon'] as $field) {
            if ($request->hasFile($field)) {
                if ($setting->$field) Storage::disk('public')->delete($setting->$field);
                $data[$field] = $request->file($field)->store('seo', 'public');
            } else {
                unset($data[$field]);
            }

            if ($request->boolean('clear_' . $field)) {
                if ($setting->$field) Storage::disk('public')->delete($setting->$field);
                $data[$field] = null;
            }
        }

        $setting->update($data);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
