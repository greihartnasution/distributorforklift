<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HomepageTestimonialRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'position'   => ['nullable', 'string', 'max:255'],
            'company'    => ['nullable', 'string', 'max:255'],
            'quote'      => ['required', 'string', 'max:1000'],
            'image'      => ['nullable', 'string', 'max:500'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'clear_image'=> ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['boolean'],
        ];
    }
}
