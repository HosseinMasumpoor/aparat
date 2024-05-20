<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBannerAttribute()
    {
        return asset('storage/' . $this->attributes['banner']);
    }

    public function setSocialsAttribute($socials)
    {
        if (is_array($socials))  $socials = json_encode($socials);
        $this->attributes['socials'] = $socials;
    }

    public function getSocialsAttribute()
    {
        return json_decode($this->attributes['socials']);
    }
}
