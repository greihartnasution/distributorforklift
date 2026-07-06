<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $productId = $this->route('product')?->id;

        return [
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'name'                => ['required', 'string', 'max:255'],
            'slug'                => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $productId],
            'short_description'   => ['nullable', 'string'],
            'full_description'    => ['nullable', 'string'],
            'specs'               => ['nullable', 'array'],
            'specs.*.label'       => ['required_with:specs', 'string', 'max:100'],
            'specs.*.value'       => ['required_with:specs', 'string', 'max:255'],
            'thumbnail'                  => ['nullable', 'image:allow_svg', 'max:3072'],
            'clear_thumbnail'            => ['boolean'],
            'thumbnail_url'              => ['nullable', 'url', 'max:500'],
            'image'                      => ['nullable', 'image:allow_svg', 'max:3072'],
            'clear_image'                => ['boolean'],
            'image_url'                  => ['nullable', 'url', 'max:500'],
            'video_url'                  => ['nullable', 'url', 'max:500'],
            'highlights'                 => ['nullable', 'array'],
            'highlights.*.title'         => ['required_with:highlights', 'string', 'max:255'],
            'highlights.*.description'   => ['nullable', 'string'],
            'highlights.*.image_url'     => ['nullable', 'string', 'max:500'],
            'highlight_image'            => ['nullable', 'string', 'max:500'],
            'highlight_image_file'       => ['nullable', 'image:allow_svg', 'max:3072'],
            'clear_highlight_image'      => ['boolean'],
            'highlight_item_images'      => ['nullable', 'array'],
            'highlight_item_images.*'    => ['nullable', 'image:allow_svg', 'max:2048'],
            'details'                    => ['nullable', 'array'],
            'details.*.title'            => ['required_with:details', 'string', 'max:255'],
            'details.*.content'          => ['nullable', 'string'],
            'details.*.icon_image'       => ['nullable', 'string', 'max:500'],
            'details.*.image'            => ['nullable', 'string', 'max:500'],
            'detail_icon_images'         => ['nullable', 'array'],
            'detail_icon_images.*'       => ['nullable', 'image:allow_svg', 'max:2048'],
            'detail_body_images'         => ['nullable', 'array'],
            'detail_body_images.*'       => ['nullable', 'image:allow_svg', 'max:3072'],
            'solutions_title'            => ['nullable', 'string', 'max:255'],
            'solutions_description'      => ['nullable', 'string'],
            'solutions'                          => ['nullable', 'array'],
            'solutions.*.label'                  => ['required_with:solutions', 'string', 'max:255'],
            'solutions.*.items'                  => ['nullable', 'array'],
            'solutions.*.items.*.label'          => ['required_with:solutions.*.items', 'string', 'max:255'],
            'solutions.*.items.*.media_type'     => ['nullable', 'in:video,image'],
            'solutions.*.items.*.video_url'      => ['nullable', 'url', 'max:500'],
            'solutions.*.items.*.image_url'      => ['nullable', 'url', 'max:500'],
            'solutions.*.items.*.content'        => ['nullable', 'string'],
            'solution_item_images'               => ['nullable', 'array'],
            'solution_item_images.*'             => ['nullable', 'image:allow_svg', 'max:3072'],
            'media_items'                => ['nullable', 'array'],
            'media_items.*.type'         => ['required_with:media_items', 'in:image,video'],
            'media_items.*.title'        => ['nullable', 'string', 'max:255'],
            'media_items.*.image'        => ['nullable', 'string', 'max:500'],
            'media_items.*.video_url'    => ['nullable', 'url', 'max:500'],
            'media_items.*.date'         => ['nullable', 'string', 'max:50'],
            'media_items.*.description'  => ['nullable', 'string'],
            'media_images'               => ['nullable', 'array'],
            'media_images.*'             => ['nullable', 'image:allow_svg', 'max:3072'],
            'model_overview'                => ['nullable', 'array'],
            'model_overview.columns'        => ['nullable', 'array'],
            'model_overview.columns.*'      => ['nullable', 'string', 'max:255'],
            'model_overview.rows'           => ['nullable', 'array'],
            'model_overview.rows.*'         => ['nullable', 'array'],
            'model_overview.rows.*.*'       => ['nullable', 'string', 'max:255'],
            'downloads'                  => ['nullable', 'array'],
            'downloads.*.title'          => ['required_with:downloads', 'string', 'max:255'],
            'downloads.*.file'           => ['nullable', 'string', 'max:500'],
            'downloads.*.size'           => ['nullable', 'string', 'max:50'],
            'download_files'             => ['nullable', 'array'],
            'download_files.*'           => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,zip', 'max:10240'],
            'hero_cert_badge_1'          => ['nullable', 'string', 'max:500'],
            'hero_cert_badge_1_file'     => ['nullable', 'image:allow_svg', 'max:2048'],
            'clear_hero_cert_badge_1'    => ['boolean'],
            'hero_cert_badge_2'          => ['nullable', 'string', 'max:500'],
            'hero_cert_badge_2_file'     => ['nullable', 'image:allow_svg', 'max:2048'],
            'clear_hero_cert_badge_2'    => ['boolean'],
            'is_active'                  => ['boolean'],
        ];
    }
}
