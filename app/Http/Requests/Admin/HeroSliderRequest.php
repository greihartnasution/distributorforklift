<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HeroSliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        // On create, an image is required — either an upload or a URL.
        $imageRules     = ['nullable', 'string', 'max:500'];
        $imageFileRules = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'];
        if (! $isUpdate) {
            $imageRules[]     = 'required_without:image_file';
            $imageFileRules[] = 'required_without:image';
        }

        return [
            'label'    => ['required', 'string', 'max:100'],
            'title'    => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'show_cta' => ['boolean'],
            'cta_text' => ['nullable', 'string', 'max:100', 'required_if:show_cta,true'],
            'cta_url'  => ['nullable', 'string', 'max:255', 'required_if:show_cta,true'],
            'image'       => $imageRules,
            'image_file'  => $imageFileRules,
            'clear_image' => ['boolean'],
            'order'    => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'label'       => 'Label',
            'title'       => 'Judul',
            'description' => 'Deskripsi',
            'show_cta'    => 'Tampilkan CTA',
            'cta_text'    => 'Teks Tombol',
            'cta_url'     => 'URL Tombol',
            'image'       => 'Gambar',
            'image_file'  => 'Gambar',
            'order'       => 'Urutan',
            'is_active'   => 'Status Aktif',
        ];
    }
}
