<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\ChangeEmailRequest;
use App\Http\Requests\Auth\ChangeEmailVerifyRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendCodeRequest;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    const CHANGE_EMAIL_CACHE_KEY = 'change.email.for.user.';
    //
    public function register(RegisterUserRequest $request)
    {
        return UserService::registerNewUser($request);
    }

    public function registerVerify(RegisterVerifyUserRequest $request)
    {
        return UserService::registerVerify($request);
    }

    public function resendVerificationCode(ResendCodeRequest $request)
    {
        return UserService::resendVerificationCode($request);
    }

    public function changeEmail(ChangeEmailRequest $request)
    {
        return UserService::changeEmail($request);
    }

    public function changeEmailVerify(ChangeEmailVerifyRequest $request)
    {
        return UserService::changeEmailVerify($request);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        return UserService::changePassword($request);
    }
}
