<?php

namespace App\Services;

use App\Http\Requests\Channel\FollowRequest;
use App\Http\Requests\Channel\UnfollowRequest;
use App\Http\Requests\Channel\UpdateBannerRequest;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Models\Channel;
use DB;
use Exception;
use Illuminate\Http\Request;
use Storage;

class ChannelService extends BaseService
{
    public static function update(UpdateChannelRequest $request, Channel $channel)
    {
        $channel = $channel->exists ? $channel : auth()->user()->channel;
        try {

            DB::beginTransaction();

            $channel->update([
                'name' => $request->name,
                'info' => $request->info
            ]);

            $channel->user()->update($request->only('website'));

            DB::commit();
            return response([
                'message' => 'اطلاعات کانال شما با موفقیت ویرایش شد',
                'data' => $channel
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response([
                // 'message' => 'ویرایش اطلاعات موفقیت آمیز نبود',
                'message' => $ex->getMessage(),
            ], 500);
        }
    }

    public static function follow(FollowRequest $request, Channel $channel)
    {
        $user = auth()->user();

        try {
            $user->followings()->attach($channel->user->id);
        } catch (\Throwable $th) {
            return response([
                'message' => 'دنبال کردن کانال به درستی انجام نشد'
            ], 500);
        }
        return response([
            'message' => 'از این به بعد کانال انتخاب شده را دنبال می کنید'
        ]);
    }

    public static function unfollow(UnfollowRequest $request, Channel $channel)
    {
        $user = auth()->user();

        try {
            $user->followings()->detach($channel->user->id);
        } catch (\Throwable $th) {
            return response([
                'message' => 'لغو دنبال کردن کانال به درستی انجام نشد'
            ], 500);
        }
        return response([
            'message' => 'از این به بعد کانال انتخاب شده را دنبال نمیکنید'
        ]);
    }

    public static function statistics(Request $request, Channel $channel)
    {
        $user = auth()->user();

        $totalComments = $user->videos()->join('comments', 'comments.video_id', '=', 'videos.id')->selectRaw('count(*) as total_comments')->get();

        $totalComments = $totalComments->pluck('total_comments')[0];

        $viewsData = $user->videos()
            ->join('video_views', 'video_views.video_id', '=', 'videos.id')
            ->selectRaw('DATE(video_views.created_at) as view_date, COUNT(video_views.id) as view_count')
            ->groupByRaw('DATE(video_views.created_at)')
            ->pluck('view_count', 'view_date');

        $totalFollowers = $user->followers()->count();
        $totalVideos = $user->videos()->count();

        return response([
            'views' => $viewsData,
            'total_followers' => $totalFollowers,
            'total_videos' => $totalVideos,
            'total_comments' => $totalComments
        ]);
    }

    public static function updateBanner(UpdateBannerRequest $request)
    {
        $channel = auth()->user()->channel;
        try {
            $image = $request->file('image');
            if ($channel->banner) {
                Storage::delete($channel->getRawOriginal('banner'));
            }
            $src = $image->store(env('CHANNEL_BANNER_UPLOAD_PATH', 'channel-banners'));

            $channel->update([
                'banner' => $src
            ]);
            return response([
                'message' => 'بنر کانال شما با موفقیت ویرایش شد',
                'data' => $channel
            ]);
        } catch (Exception $ex) {
            return response([
                'message' => $ex->getMessage(),
            ], 500);
        }
    }

    public static function updateSocials(UpdateSocialsRequest $request)
    {
        $channel = auth()->user()->channel;

        $socials = [
            'telegram' => $request->get('telegram'),
            'instagram' => $request->get('instagram'),
            'youtube' => $request->get('youtube'),
            'linkedin' => $request->get('linkedin'),
            'twitter' => $request->get('twitter'),
        ];

        try {
            $channel->update([
                'socials' => $socials
            ]);

            return response([
                'message' => 'اطلاعات شبکه های اجتماعی شما با موفقیت ویرایش شد',
                'channel' => $channel
            ]);
        } catch (Exception $ex) {
            return response([
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
}
