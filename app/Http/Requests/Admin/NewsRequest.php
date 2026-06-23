<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $newsId = $this->route('news')?->id;

        return [
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', 'max:255', Rule::unique('news', 'slug')->ignore($newsId)],
            'category'     => ['required', 'string', 'max:100'],
            'excerpt'      => ['nullable', 'string', 'max:500'],
            'content'      => ['required', 'string'],
            'image'        => ['nullable', 'string', 'max:500'],
            'image_file'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'clear_image'  => ['boolean'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title'        => 'Judul',
            'slug'         => 'Slug',
            'category'     => 'Kategori',
            'excerpt'      => 'Ringkasan',
            'content'      => 'Konten',
            'image'        => 'Gambar',
            'image_file'   => 'Gambar',
            'is_published' => 'Status Publikasi',
            'published_at' => 'Tanggal Publikasi',
        ];
    }
}
