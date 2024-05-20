<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VideoLike extends Pivot
{
    protected $table = 'video_favorites';

    protected $fillable = [
        'user_id',
        'user_ip',
        'video_id'
    ];
}
