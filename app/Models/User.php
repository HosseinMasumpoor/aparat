<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const TYPE_USER = 'user';
    const TYPE_ADMIN = 'admin';

    const TYPES  = [self::TYPE_USER, self::TYPE_ADMIN];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'avatar',
        'website',
        'verify_code',
        'verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relations
     */
    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function videosWithRepublishedStatus()
    {
        return $this->videos()->selectRaw('*, 0 as republished');
    }

    public function allVideos()
    {
        return $this->videosWithRepublishedStatus()->union($this->videoRepublishes());
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function videoRepublishes()
    {
        return $this->hasManyThrough(Video::class, VideoRepublish::class, 'user_id', 'id', 'id', 'video_id')->selectRaw('videos.*, 1 as republished');
    }

    public function videoLikes()
    {
        return $this->belongsToMany(Video::class, 'video_favorites');
    }

    public function videoViews()
    {
        return $this->belongsToMany(Video::class, 'video_views')->withTimestamps();
    }

    public function findForPassport($username)
    {
        $user = self::where('email', $username)->orWhere('mobile', $username)->first();
        return $user;
    }

    public function setMobileAttribute($value)
    {
        // $mobile = "+98" . substr($value, -10, 10);
        $mobile = toStandardMobile($value);
        $this->attributes["mobile"] = $mobile;
    }

    public function isAdmin()
    {
        return $this->type == self::TYPE_ADMIN;
    }

    public function isUser()
    {
        return $this->type == self::TYPE_USER;
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }
}
