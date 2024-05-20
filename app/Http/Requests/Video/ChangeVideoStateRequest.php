<?php

namespace App\Http\Requests\Video;

use App\Models\Video;
use App\Rules\ValidVideoState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Route;

class ChangeVideoStateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('changeState', $this->video);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $video = Route::current()->parameter('video');
        return [
            'state' => ['required', 'string', new ValidVideoState($video)]
        ];
    }
}
