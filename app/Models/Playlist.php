<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
    ];

    /**
     * Relations
     */

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['size'] = $this->videos()->count();
        return $data;
    }
}
