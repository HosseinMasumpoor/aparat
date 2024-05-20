<?php

namespace App\Http\Requests;

use App\Rules\CategoryBannerUploadedRule;
use App\Rules\UniqueForUser;
use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
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
            'title' => ['required', 'string', new UniqueForUser("categories")],
            'icon' => 'nullable|string',
            'banner' => ['nullable', 'string', new CategoryBannerUploadedRule],
        ];
    }
}
