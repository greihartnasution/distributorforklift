<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InquirySettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'heading'            => ['nullable', 'string', 'max:255'],
            'intro'              => ['nullable', 'string', 'max:1000'],
            'hours'              => ['nullable', 'string', 'max:100'],
            'consultant_name'    => ['nullable', 'string', 'max:255'],
            'consultant_title'   => ['nullable', 'string', 'max:255'],
            'consultant_company' => ['nullable', 'string', 'max:255'],
            'consultant_phone'   => ['nullable', 'string', 'max:50'],
            'consultant_email'   => ['nullable', 'email', 'max:255'],
            'consultant_photo'      => ['nullable', 'string', 'max:500'],
            'consultant_photo_file' => ['nullable', 'image', 'max:1024'],
            'clear_photo'        => ['boolean'],
            'instagram'          => ['nullable', 'url', 'max:255'],
            'tiktok'             => ['nullable', 'url', 'max:255'],
            'youtube'            => ['nullable', 'url', 'max:255'],
            'facebook'           => ['nullable', 'url', 'max:255'],
        ];
    }
}
