<?php

namespace App\Rules;

use App\Models\Video;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidVideoState implements ValidationRule
{

    public function __construct(private Video $video)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currentState = $this->video->state;
        $isNotValid = (
            (!in_array($value, Video::STATES)) ||
            ($currentState == Video::STATE_PENDING) ||
            ($value == Video::STATE_PENDING) ||
            ($value == Video::STATE_CONVERTED)
        );
        if ($isNotValid) {
            $fail('وضعیت انتخاب شده معتبر نیست');
        }
    }
}
