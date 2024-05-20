<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Storage;

class UploadVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private Video $video, private string $videoPath, private string $uploadPath, private $userName, private bool $watermark = false)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $video = $this->video;
        // Get video file for get information from it
        /** @var \ProtoneMedia\LaravelFFMpeg\MediaOpener $video */
        $videoFile = FFMpeg::fromDisk('public')
            ->open($this->videoPath);

        $duration = $videoFile->getDurationInSeconds();
        $format = new X264('libmp3lame');

        if ($this->watermark) {
            $filter = new CustomFilter("drawtext=text='$this->userName': fontcolor=blue: fontsize=24: box=1: boxcolor=white@0.5: boxborderw=5: x=10: y=(h - text_h - 10)");
            $videoFile = $videoFile->addFilter($filter);
        }

        $videoFile = $videoFile->export()->toDisk('public')->inFormat($format);
        $videoFile->save($this->uploadPath);

        Storage::delete($this->videoPath);

        $video->duration = $duration;
        $video->state = Video::STATE_CONVERTED;
        $video->save();
    }
}
