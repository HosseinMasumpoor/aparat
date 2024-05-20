<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\FollowRequest;
use App\Http\Requests\Channel\UnfollowRequest;
use App\Http\Requests\Channel\UpdateBannerRequest;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Models\Channel;
use App\Models\User;
use App\Services\ChannelService;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    //

    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        return ChannelService::update($request, $channel);
    }

    public function follow(FollowRequest $request, Channel $channel)
    {
        return ChannelService::follow($request, $channel);
    }

    public function unfollow(UnfollowRequest $request, Channel $channel)
    {
        return ChannelService::unfollow($request, $channel);
    }

    public function statistics(Request $request, Channel $channel)
    {
        return ChannelService::statistics($request, $channel);
    }

    public function updateBanner(UpdateBannerRequest $request)
    {
        return ChannelService::updateBanner($request);
    }

    public function updateSocials(UpdateSocialsRequest $request)
    {
        return ChannelService::updateSocials($request);
    }
}
