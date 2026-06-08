<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquirySetting extends Model
{
    protected $table = 'inquiry_settings';

    protected $fillable = [
        'heading', 'intro', 'hours',
        'consultant_name', 'consultant_title', 'consultant_company',
        'consultant_phone', 'consultant_email', 'consultant_photo',
        'links_label', 'link_1', 'link_2', 'link_3',
    ];
}
