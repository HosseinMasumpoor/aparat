<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueChannelName implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('~^[a-z-A-Z_][a-z-A-Z0-9\-_]{3,250}$~', $value)) {
            $fail('فقط حروف انگلیسی (کوچک و بزرگ) ، اعداد، علامت - و _ مجاز است');
        }
    }
}
