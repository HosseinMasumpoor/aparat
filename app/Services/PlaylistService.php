<?php

namespace App\Services;

use App\Http\Requests\Category\UploadBannerRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\Playlist\PlaylistStoreRequest;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\User;
use Storage;
use Str;

class PlaylistService extends BaseService
{
    public static function my()
    {
        return auth()->user()->playlists;
    }

    public static function store(PlaylistStoreRequest $request)
    {

        try {
            $playlist = Playlist::create([
                'title' => $request->title,
                'user_id' => auth()->id()
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'لیست پخش با موفقیت افزوده نشد'
            ], 500);
        }

        return response([
            'message' => 'لیست پخش با موفقیت افزوده شد',
            'data' => $playlist
        ]);
    }
}
