<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageClient extends Model
{
    protected $fillable = ['name', 'logo', 'website', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
