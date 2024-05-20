<?php

namespace App\Listeners;

use App\Events\UploadNewVideo;
use App\Jobs\UploadVideoJob;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Storage;

class ProcessUploadVideo
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
    public function handle(UploadNewVideo $event): void
    {
        UploadVideoJob::dispatch($event->video, $event->request->video_path, $event->uploadPath, $event->userName, $event->request->watermark_enabled);
    }
}
