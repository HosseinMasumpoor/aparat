<?php

if (!function_exists("toStandardMobile")) {
    function toStandardMobile($mobile)
    {
        return "+98" . substr($mobile, -10, 10);
    }
}


if (!function_exists("generate_verification_code")) {
    function generate_verification_code()
    {
        return random_int(100000, 999999);
    }
}

if (!function_exists("clean_directory")) {
    function clean_directory($path)
    {
        try {
            foreach (Storage::allDirectories($path) as $directory) {
                Storage::deleteDirectory($directory);
            }
            Storage::delete(Storage::allFiles($path));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return false;
        }
        return true;
    }
}

if (!function_exists("client_ip")) {
    function client_ip()
    {
        return $_SERVER['REMOTE_ADDR'] . '-' . md5($_SERVER['HTTP_USER_AGENT']);
    }
}
