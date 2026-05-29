<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ContactPageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'heading'       => ['nullable', 'string', 'max:500'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'content_left'  => ['nullable', 'string'],
            'content_right' => ['nullable', 'string'],
            'show_inquiry'  => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'heading'       => 'Heading',
            'description'   => 'Deskripsi',
            'image'         => 'Gambar',
            'content_left'  => 'Konten Kolom Kiri',
            'content_right' => 'Konten Kolom Kanan',
            'show_inquiry'  => 'Sertakan Formulir Kontak',
        ];
    }
}
