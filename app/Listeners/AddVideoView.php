<?php

namespace App\Listeners;

use App\Events\VideoViewEvent;
use App\Models\VideoView;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddVideoView
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VideoViewEvent $event): void
    {
        if (auth('api')->check()) {
            $userId = auth('api')->id();

            $conditions = [
                'user_id' => $userId,
                ['video_views.created_at', '>', now()->subDay()]
            ];

            if ($event->video->views()->where($conditions)->count() == 0) {
                $event->video->views()->attach($userId);
            }
        } else {

            $conditions = [
                'user_ip' => client_ip(),
                ['video_views.created_at', '>', now()->subDay()]
            ];

            if (VideoView::where($conditions)->count() == 0) {
                VideoView::create([
                    'user_ip' => client_ip(),
                    'video_id' => $event->video->id
                ]);
            }
        }
    }
}
