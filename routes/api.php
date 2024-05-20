<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'as' => 'passport.',
    'namespace' => '\Laravel\Passport\Http\Controllers',
    'prefix' => 'auth',
], function () {
    Route::post('/login', [AccessTokenController::class, 'issueToken'])->middleware(['throttle'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('register-verify', [AuthController::class, 'registerVerify'])->name('register.verify');
    Route::post('resend-verification-code', [AuthController::class, 'resendVerificationCode'])->name('resendVerificationCode');
});

Route::middleware('auth:api')->prefix('user')->name('user.')->group(function () {
    Route::post('/change-email', [AuthController::class, 'changeEmail'])->name('changeEmail');
    Route::post('/change-email-verify', [AuthController::class, 'changeEmailVerify'])->name('changeEmailVerify');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.update');
    Route::get('/followings', [UserController::class, 'followingList'])->name('following.list');
    Route::get('/followers', [UserController::class, 'followerList'])->name('follower.list');
});

Route::middleware('auth:api')->prefix('channel')->name('channel.')->group(function () {
    Route::put('/{channel?}', [ChannelController::class, 'update'])->name('update');
    Route::post('/', [ChannelController::class, 'updateBanner'])->name('banner.update');
    Route::post('/{channel:name}/follow', [ChannelController::class, 'follow'])->name('follow');
    Route::post('/{channel:name}/unfollow', [ChannelController::class, 'unfollow'])->name('unfollow');
    Route::match(['put', 'post'], '/socials', [ChannelController::class, 'updateSocials'])->name('socials.update');
    Route::get('/statistics', [ChannelController::class, 'statistics']);
});

Route::prefix('video')->name('video.')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::post('/upload', [VideoController::class, 'upload']);
        Route::post('/', [VideoController::class, 'store']);
        Route::put('/{video:slug}/change-state', [VideoController::class, 'changeState']);
        Route::get('/', [VideoController::class, 'list']);
        Route::get('/user', [VideoController::class, 'userVideoList']);
        Route::get('/user/liked', [VideoController::class, 'likedVideos']);
        Route::post('/{video:slug}/republish', [VideoController::class, 'republish']);
    });
    Route::post('/{video:slug}/like', [VideoController::class, 'like']);
    Route::get('/{video:slug}', [VideoController::class, 'show']);
});

Route::middleware('auth:api')->prefix('category')->name('category.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('all');
    Route::get('/personal', [CategoryController::class, 'getPersonals'])->name('personals');
    Route::post('/banner/upload', [CategoryController::class, 'uploadBanner'])->name('banner.upload');
    Route::post('/store', [CategoryController::class, 'store'])->name('store');
});

Route::middleware('auth:api')->prefix('playlist')->name('playlist.')->group(function () {
    Route::get('/my', [PlaylistController::class, 'my'])->name('my');
    Route::post('/store', [PlaylistController::class, 'store'])->name('store');
});

Route::middleware('auth:api')->prefix('tag')->name('tag.')->group(function () {
    Route::get('/', [TagController::class, 'index'])->name('index');
    Route::post('/store', [TagController::class, 'store'])->name('store');
});
