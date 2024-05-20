<?php

namespace App\Services;

use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\ChangeEmailRequest;
use App\Http\Requests\Auth\ChangeEmailVerifyRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendCodeRequest;
use App\Models\User;
use Cache;
use Exception;
use Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Log;

class UserService extends BaseService
{
    const CHANGE_EMAIL_CACHE_KEY = 'change.email.for.user.';
    public static function registerNewUser(RegisterUserRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $user = User::where($field, $value)->first();
        if ($user) {
            if ($user->verified_at) {
                throw new UserAlreadyRegisteredException("کاربر مورد نظر قبلا ثبت نام کرده است");
            }

            return response([
                'message' => 'کد فعال سازی قبلا برای شما ارسال شده است'
            ]);
        }
        $code = generate_verification_code();
        $user = User::create([
            $field => $value,
            'verify_code' => $code,
        ]);

        Log::info("SEND-REGISTER-CODE-MESSAGE-TO-USER", ['code' => $code]);

        return response([
            'message' => 'کاربر ثبت موقت شد'
        ]);
    }

    public static function registerVerify(RegisterVerifyUserRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        if ($field == 'mobile') $value = toStandardMobile($value);

        $user = User::where(['verify_code' => $request->code, $field => $value])->first();
        if ($user) {
            $user->update([
                'verify_code' => null,
                'verified_at' => now(),
            ]);
            return response([
                'message' => 'success',
                'data' => $user
            ]);
        }
        throw new ModelNotFoundException("کد وارد شده صحیح نیست");
    }

    public static function resendVerificationCode(ResendCodeRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();
        $user = User::where($field, $value)->whereNull('verified_at')->first();
        // dd($user, $field, $value);
        if (!$user) {
            throw new ModelNotFoundException("کاربری با این مشخصات یافت نشد");
        }
        $elapsedTime = now()->diffInMinutes($user->updated_at);
        if ($elapsedTime > config('auth.resend_verification_code_time')) {
            $code = generate_verification_code();
            $user->verify_code = $code;
            $user->save();
        }
        Log::info("RESEND_VERIFICATION_CODE_MESSAGE_TO_USER", ['code' => $user->verify_code]);

        return response([
            'message' => 'کد مجددا برای شما ارسال گردید'
        ]);
    }

    public static function changeEmailVerify(ChangeEmailVerifyRequest $request)
    {
        $email = $request->email;
        $code = $request->code;
        $user = auth()->user();

        $cache = Cache::get(self::CHANGE_EMAIL_CACHE_KEY . $user->id);
        if (!$cache || $cache['email'] !== $email || $cache['code'] != $code) {
            return response([
                'message' => 'تغییر ایمیل با موفقیت انجام نشد'
            ], 400);
        }

        $user->email = $cache['email'];
        $user->save();

        Cache::forget(self::CHANGE_EMAIL_CACHE_KEY . $user->id);

        return response([
            'message' => 'ایمیل شما با موفقیت تغییر یافت'
        ]);
    }

    public static function changeEmail(ChangeEmailRequest $request)
    {
        $email = $request->email;
        $userId = auth()->id();
        $code = generate_verification_code();
        $codeExpirationTime = now()->addMinutes(config('auth.change_email_cache_expiration'));

        try {

            Cache::put(self::CHANGE_EMAIL_CACHE_KEY . $userId, compact('email', 'code'), $codeExpirationTime);

            Log::info("CHANGE_EMAIL_CODE_MESSAGE_TO_USER", compact('code'));
            return response([
                'message' => 'کد تأیید به ایمیل شما ارسال شد'
            ]);
        } catch (Exception $ex) {
            return response([
                'message' => $ex->getMessage()
            ]);
        }
    }

    public static function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        if (!Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'رمز عبور وارد شده اشتباه است'
            ], 400);
        }

        try {
            $user->update([
                'password' => $request->new_password
            ]);

            return response([
                'message' => 'عملیات با موفقیت انجام شد',
                'user' => $user
            ]);
        } catch (\Throwable $ex) {
            return response([
                'message' => $ex->getMessage()
            ]);
        }
    }

    public static function followingList(Request $request)
    {
        return auth()->user()->followings()->paginate();
    }

    public static function followerList(Request $request)
    {
        return auth()->user()->followers()->paginate();
    }
}
