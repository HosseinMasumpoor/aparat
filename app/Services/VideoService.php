<?php

namespace App\Services;

use App\Events\UploadNewVideo;
use App\Events\VideoViewEvent;
use App\Http\Requests\Video\ChangeVideoStateRequest;
use App\Http\Requests\Video\VideoLikeRequest;
use App\Http\Requests\Video\VideoListRequest;
use App\Http\Requests\Video\VideoRepublishRequest;
use App\Http\Requests\Video\VideoStoreRequest;
use App\Http\Requests\Video\VideoUploadRequest;
use App\Models\Playlist;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoLike;
use App\Models\VideoRepublish;
use DB;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Format\Video\X264;
// use FFMpeg\Format\Video\WMV;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg as SupportFFMpeg;
use Storage;
use Illuminate\Http\Request;
use Str;

class VideoService extends BaseService
{
    public static function list(VideoListRequest $request)
    {
        return Video::paginate();
    }

    public static function show(Request $request, Video $video)
    {
        event(new VideoViewEvent($video));
        return $video;
    }

    public static function userVideoList(VideoListRequest $request)
    {

        $user = auth()->user();

        if ($request->has('republished')) {
            $videos = $request->republished ? $user->videoRepublishes() : $user->videosWithRepublishedStatus();
        } else {
            $videos = $user->allVideos();
        }
        return $videos->paginate();
    }

    public static function likedVideos(Request $request)
    {

        $user = auth()->user();

        return $user->videoLikes()->paginate();
    }

    public static function upload(VideoUploadRequest $request)
    {
        try {
            $video = $request->file('video');
            $videoPath = $video->store(env('VIDEO_TEMP_UPLOAD_PATH'), 'public');
            return response([
                'video' => $videoPath,
                'message' => 'ویدئو با موفقیت آپلود شد'
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'ویدئو با موفقیت آپلود نشد'
            ], 500);
        }
    }

    public static function store(VideoStoreRequest $request)
    {

        $userName = auth()->user()->name;
        // Find file name and destination path
        $fileName = Str::replace(env('VIDEO_TEMP_UPLOAD_PATH') . '/', '', $request->video_path);
        $destPath = env('VIDEO_UPLOAD_PATH') . '/' . auth()->id() . '/';

        $uploadPath = $destPath . '/' . $fileName . '.mp4';

        // Store banner file
        $banner = $request->file('banner');
        if ($banner)
            $bannerPath = $banner->store(env('VIDEO_BANNER_UPLOAD_PATH'));
        else
            $bannerPath = null;

        try {
            DB::beginTransaction();

            $video = Video::create([
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
                'channel_category_id' => $request->channel_category,
                'title' => $request->title,
                'info' => $request->info,
                'duration' => 0,
                'banner' => $bannerPath,
                'publish_at' => $request->publish_at,
                'comment_enabled' => $request->comment_enabled,
                'state' => Video::STATE_PENDING
            ]);
            event(new UploadNewVideo($video, $request, $uploadPath, $userName));

            if ($request->playlist) {
                $playlist = Playlist::find($request->playlist);
                $playlist->videos()->save($video);
            }

            $video->tags()->attach($request->tags);

            DB::commit();

            return response([
                'message' => 'ویدئو با موفقیت آپلود شد',
                'video' => $video
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                'message' => 'ویدئو با موفقیت ذخیره نشد',
                'Exception' => $th->getMessage()
            ], 500);
        }
    }

    public static function changeState(Video $video, ChangeVideoStateRequest $request)
    {
        try {
            $video->update($request->all());
        } catch (\Throwable $th) {
            return response([
                'message' => 'وضعیت ویدئو تغییر پیدا نکرد'
            ], 500);
        }

        return response([
            'message' => 'وضعیت ویدئو با موفقیت تغییر پیدا کرد',
            'data' => $video
        ]);
    }

    public static function republish(Video $video, VideoRepublishRequest $request)
    {
        $user = auth()->user();
        try {
            VideoRepublish::create([
                'user_id' => $user->id,
                'video_id' => $video->id
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return response([
                'message' => 'ویدئو با موفقیت بازنشر نشد'
            ], 500);
        }

        return response([
            'message' => 'ویدئو با موفقیت بازنشر شد'
        ]);
    }

    public static function like(Video $video, VideoLikeRequest $request)
    {
        $user = auth('api')->user();

        if (!$user) {
        }

        try {
            if ($user) {
                $user->videoLikes()->toggle($video);
            } else {
                $like = VideoLike::where(['video_id' => $video->id, 'user_ip' => client_ip()])->first();
                if ($like) {
                    $like->delete();
                } else {

                    VideoLike::create([
                        'user_id' => null,
                        'user_ip' => client_ip(),
                        'video_id' => $video->id
                    ]);
                }
            }
        } catch (\Throwable $th) {
            dd($th);
            return response([
                'message' => 'عملیات با موفقیت انجام نشد'
            ], 500);
        }
        return response([
            'message' => 'عملیات با موفقیت انجام شد'
        ]);
    }
}
