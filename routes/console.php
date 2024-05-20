<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('data:clear', function () {
    clean_directory(env('VIDEO_BANNER_UPLOAD_PATH'));
    $this->info('video banner files deleted');

    clean_directory(env('VIDEO_UPLOAD_PATH'));
    $this->info('video files deleted');

    clean_directory(env('CHANNEL_BANNER_UPLOAD_PATH'));
    $this->info('channel banners deleted');

    clean_directory(env('CATEGORY_BANNER_UPLOAD_PATH'));
    $this->info('category banners deleted');
})->purpose('Clean all temporary files');
