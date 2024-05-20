<?php

namespace App\Services;

use App\Http\Requests\Tag\TagStoreRequest;
use App\Models\Tag;

class TagService extends BaseService
{
    public static function getAll()
    {
        return Tag::all('id', 'title');
    }

    public static function store(TagStoreRequest $request)
    {

        try {
            $tag = Tag::create([
                'title' => $request->title,
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'برچسب با موفقیت افزوده نشد'
            ], 500);
        }

        return response([
            'message' => 'برچسب با موفقیت افزوده شد',
            'data' => $tag
        ]);
    }
}
