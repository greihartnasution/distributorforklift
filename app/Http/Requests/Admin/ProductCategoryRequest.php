<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCategoryRequest extends FormRequest
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
        $categoryId = $this->route('category')?->id;
        $parentId   = $this->input('parent_id');

        $slugUnique = Rule::unique('product_categories', 'slug')
            ->ignore($categoryId);

        if ($parentId) {
            $slugUnique->where('parent_id', $parentId);
        } else {
            $slugUnique->whereNull('parent_id');
        }

        return [
            'parent_id'   => ['nullable', 'exists:product_categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', $slugUnique],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'string', 'max:500'],
            'image_file'  => ['nullable', 'image:allow_svg', 'max:2048'],
            'clear_image' => ['boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
            'nav_group'   => ['nullable', 'string', 'max:255'],
            'nav_sub'     => ['nullable', 'string', 'max:255'],
            'nav_label'   => ['nullable', 'string', 'max:255'],
            'show_in_nav' => ['boolean'],
            'show_inquiry' => ['boolean'],
        ];
    }
}
