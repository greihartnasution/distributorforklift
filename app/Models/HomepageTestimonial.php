<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageTestimonial extends Model
{
    protected $fillable = ['name', 'position', 'company', 'quote', 'image', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
