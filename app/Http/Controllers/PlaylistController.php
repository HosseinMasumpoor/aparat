<?php

namespace App\Http\Controllers;

use App\Http\Requests\Playlist\PlaylistStoreRequest;
use App\Models\Playlist;
use App\Services\PlaylistService;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function my()
    {
        return PlaylistService::my();
    }

    public function store(PlaylistStoreRequest $request)
    {
        return PlaylistService::store($request);
    }
}
