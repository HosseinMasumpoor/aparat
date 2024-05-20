<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    use Sluggable;

    const STATE_PENDING = "pending";
    const STATE_CONVERTED = "converted";
    const STATE_ACCEPTED = "accepted";
    const STATE_BLOCKED = "blocked";

    const STATES = [
        self::STATE_ACCEPTED,
        self::STATE_BLOCKED,
        self::STATE_CONVERTED,
        self::STATE_PENDING,
    ];


    protected $fillable = [
        'user_id',
        'category_id',
        'channel_category_id',
        'slug',
        'title',
        'info',
        'duration',
        'banner',
        'state',
        'publish_at',
    ];

    /**
     * Relations
     */
    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'video_tag');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'video_favorites');
    }

    public function views()
    {
        return $this->belongsToMany(User::class, 'video_views')->withTimestamps();
    }

    public function viewsCount()
    {
        return $this->views()->count();
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function toArray()
    {
        $data = parent::toArray();

        $conditions = auth()->check() ? [
            'user_id' => auth()->id(),
        ] : [
            'user_ip' => client_ip()
        ];

        $data['isLiked'] = $this->likes()->where($conditions)->count();

        return $data;
    }
}
