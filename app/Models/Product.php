<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'product_category_id',
        'name',
        'slug',
        'short_description',
        'full_description',
        'specs',
        'highlights',
        'highlight_image',
        'details',
        'solutions_title',
        'solutions_description',
        'solutions',
        'media_items',
        'model_overview',
        'downloads',
        'image',
        'image_url',
        'thumbnail',
        'thumbnail_url',
        'hero_cert_badge_1',
        'hero_cert_badge_2',
        'video_url',
        'is_active',
    ];

    protected $casts = [
        'specs'          => 'array',
        'highlights'     => 'array',
        'details'        => 'array',
        'solutions'      => 'array',
        'media_items'    => 'array',
        'model_overview' => 'array',
        'downloads'      => 'array',
        'is_active'      => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
