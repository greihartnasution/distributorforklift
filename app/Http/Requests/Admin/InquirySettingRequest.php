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
            'heading' => ['nullable', 'string', 'max:255'],
            'intro'   => ['nullable', 'string', 'max:1000'],
            'hours'   => ['nullable', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'heading' => 'Heading',
            'intro'   => 'Intro',
            'hours'   => 'Jam Operasional',
        ];
    }
}
