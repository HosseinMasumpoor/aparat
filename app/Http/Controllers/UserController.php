<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function followingList(Request $request)
    {
        return UserService::followingList($request);
    }

    public function followerList(Request $request)
    {
        return UserService::followerList($request);
    }
}
