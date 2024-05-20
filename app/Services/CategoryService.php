<?php

namespace App\Services;

use App\Http\Requests\Category\UploadBannerRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use App\Models\User;
use Storage;
use Str;

class CategoryService extends BaseService
{
    public static function getAll()
    {
        return Category::whereNull('user_id')->get();
    }

    public static function getPersonals()
    {
        return Category::where('user_id', auth()->id())->get();
    }

    public static function uploadBanner(UploadBannerRequest $request)
    {
        try {
            $banner = $request->file('banner');
            $bannerPath = $banner->store(env('CATEGORY_BANNER_UPLOAD_PATH') . '/temp', 'public');
            return response([
                'banner' => $bannerPath,
                'message' => 'بنر با موفقیت آپلود شد'
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'بنر با موفقیت آپلود نشد'
            ], 500);
        }
    }

    public static function store(CategoryStoreRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();

        // Find file name and destination path
        $fileName = Str::replace(env('CATEGORY_BANNER_UPLOAD_PATH') . '/temp/', '', $request->banner);
        $destPath = env('CATEGORY_BANNER_UPLOAD_PATH') . '/' . auth()->id() . '/';

        try {
            $banner = Storage::move($request->banner, $destPath . '/' . $fileName);

            $request->merge([
                'banner' => $destPath . $fileName
            ]);
            $category = $user->categories()->create($request->all());

            return response([
                'message' => 'دسته بندی با موفقیت آپلود شد',
                'data' => $category
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'دسته بندی با موفقیت افزوده نشد'
            ], 500);
        }
    }
}
