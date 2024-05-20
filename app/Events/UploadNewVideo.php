<?php

namespace App\Events;

use App\Http\Requests\Video\VideoStoreRequest;
use App\Models\Video;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadNewVideo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $video, $request, $uploadPath, $userName;

    /**
     * Create a new event instance.
     */
    public function __construct(Video $video, VideoStoreRequest $request, $uploadPath, $userName)
    {
        $this->video = $video;
        $this->request = $request;
        $this->uploadPath = $uploadPath;
        $this->userName = $userName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
