<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPage extends Model
{
    protected $table = 'contact_page';

    protected $fillable = ['heading', 'description', 'image', 'content_left', 'content_right', 'show_inquiry'];

    protected $casts = ['show_inquiry' => 'boolean'];
}
