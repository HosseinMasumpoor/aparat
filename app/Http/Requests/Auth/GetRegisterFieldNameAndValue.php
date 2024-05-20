<?php

namespace App\Http\Requests\Auth;

trait GetRegisterFieldNameAndValue
{
    public function getFieldName()
    {
        return $this->has("email") ? "email" : "mobile";
    }

    public function getFieldValue()
    {
        $field = $this->getFieldName();
        $value = $this->input($field);

        // اگر ثبت نام با موبایل رو انتخاب کرد به شماره تلفن استاندارد تبدیلش کنه
        if ($field == "mobile") $value = toStandardMobile($value);
        return $value;
    }
}
