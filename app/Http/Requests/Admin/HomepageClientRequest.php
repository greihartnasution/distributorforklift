<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class HomepageClientRequest extends FormRequest
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
            'name'       => ['required', 'string', 'max:255'],
            'logo'       => ['nullable', 'string', 'max:500'],
            'logo_file'  => ['nullable', 'image', 'max:2048'],
            'clear_logo' => ['boolean'],
            'website'    => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['boolean'],
        ];
    }
}
