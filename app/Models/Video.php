<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'galeria_videos';

    protected $fillable = [
        'titulo',
        'url_video',
    ];
}
