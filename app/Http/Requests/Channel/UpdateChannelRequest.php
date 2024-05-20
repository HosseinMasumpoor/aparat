<?php

namespace App\Http\Requests\Channel;

use App\Models\User;
use App\Rules\UniqueChannelName;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChannelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->route()->hasParameter('channel') && auth()->user()->type != User::TYPE_ADMIN) {
            return false;
        }
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
            'name' => ['required', 'max:255', new UniqueChannelName, 'unique:channels,name,' . $this->channel->id],
            'info' => 'nullable|string|max:255',
            'website' => 'nullable|url'
        ];
    }
}
