<?php

namespace App\Http\Requests\Video;

use App\Rules\ChannelCategoryRule;
use App\Rules\ValidPlaylistRule;
use App\Rules\VideoUploadedRule;
use Illuminate\Foundation\Http\FormRequest;

class VideoStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'video_path' => ['required', new VideoUploadedRule],
            'title' => 'required',
            'category_id' => ['required', 'exists:categories,id', new ChannelCategoryRule(ChannelCategoryRule::PUBLIC_CATEGORY)],
            'banner' => 'nullable|file|mimes:png,jpg,gif,svg',
            'info' => 'required',
            'channel_category' => ['nullable', new ChannelCategoryRule(ChannelCategoryRule::PRIVATE_CATEGORY)],
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'playlist' => ['nullable', 'exists:playlists,id', new ValidPlaylistRule],
            'publish_at' => 'nullable|date_format:Y-m-d H:i:s|after:now',
            'comment_enabled' => 'boolean',
            'watermark_enabled' => 'boolean',
        ];
    }
}
