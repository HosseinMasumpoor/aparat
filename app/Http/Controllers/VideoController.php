<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\ChangeVideoStateRequest;
use App\Http\Requests\Video\VideoLikeRequest;
use App\Http\Requests\Video\VideoListRequest;
use App\Http\Requests\Video\VideoRepublishRequest;
use App\Http\Requests\Video\VideoStoreRequest;
use App\Http\Requests\Video\VideoUploadRequest;
use App\Models\Video;
use App\Services\VideoService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function list(VideoListRequest $request)
    {
        return VideoService::list($request);
    }

    public function show(Request $request, Video $video)
    {
        return VideoService::show($request, $video);
    }

    public function userVideoList(VideoListRequest $request)
    {
        return VideoService::userVideoList($request);
    }

    public function likedVideos(Request $request)
    {
        return VideoService::likedVideos($request);
    }

    public function upload(VideoUploadRequest $request)
    {
        return VideoService::upload($request);
    }

    public function store(VideoStoreRequest $request)
    {
        return VideoService::store($request);
    }

    public function changeState(Video $video, ChangeVideoStateRequest $request)
    {
        return VideoService::changeState($video, $request);
    }

    public function republish(Video $video, VideoRepublishRequest $request)
    {
        return VideoService::republish($video, $request);
    }

    public function like(Video $video, VideoLikeRequest $request)
    {
        return VideoService::like($video, $request);
    }
}
