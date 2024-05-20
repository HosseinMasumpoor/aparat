<?php

namespace App\Rules;

use App\Models\Playlist;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPlaylistRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $playlist = Playlist::find($value);

        if ($playlist->user_id != auth()->id()) {
            $fail("لیست پخش انتخاب شده اشتباه است");
        }
    }
}
