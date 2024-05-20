<?php

namespace App\Rules;

use Closure;
use DB;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueForUser implements ValidationRule
{
    private $tableName;
    private $userId;
    private $userIdFieldName;

    public function __construct(string $tableName, int $userId = null,  string $userIdFieldName = 'user_id')
    {
        $this->tableName = $tableName;
        $this->userId = $userId ?? auth()->id();
        $this->userIdFieldName = $userIdFieldName;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $count = DB::table($this->tableName)->where($attribute, $value)->where($this->userIdFieldName, $this->userId)->count();
        if ($count !== 0) {
            $fail("عنوان وارد شده تکراری است");
        }
    }
}
