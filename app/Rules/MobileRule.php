<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MobileRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $reg = "~^(0098|098|\+?98|0)9\d{9}$~";
        if (!preg_match($reg, $value))
            $fail("شماره موبایل وارد شده معتبر نیست");
    }
}
