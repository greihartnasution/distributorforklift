<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquirySetting extends Model
{
    protected $table = 'inquiry_settings';

    protected $fillable = ['heading', 'intro', 'hours'];
}
