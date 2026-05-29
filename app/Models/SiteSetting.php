<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = ['phone', 'whatsapp', 'address', 'youtube', 'instagram', 'tiktok', 'facebook'];
}
