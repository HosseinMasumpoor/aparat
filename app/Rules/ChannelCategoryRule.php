<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ChannelCategoryRule implements ValidationRule
{

    const PUBLIC_CATEGORY = "public";
    const PRIVATE_CATEGORY = "private";


    public function __construct(protected $categoryType)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $category = Category::find($value);

        if ($this->categoryType == self::PRIVATE_CATEGORY) {

            if (empty($category->user_id) || $category->user_id != auth()->id()) {
                $fail("دسته بندی انتخاب شده اشتباه است");
            }
        } else {
            if ($category->user_id) {
                $fail("دسته بندی انتخاب شده اشتباه است");
            }
        }
    }
}
