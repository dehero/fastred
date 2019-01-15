<?php

if (!function_exists('dirCreate')) {
    function dirCreate($dirpath) {
        return mkdir($dirpath, 0777, true);
    }
}

if (!function_exists('dirCreateOrExists')) {
    function dirCreateOrExists($dirpath) {
        return is_dir($dirpath) || mkdir($dirpath, 0777, true);
    }
}

if (!function_exists('dirExists')) {
    function dirExists($dirpath) {
        return is_dir($dirpath);
    }
}

if (!function_exists('dirDelete')) {
    function dirDelete($dirpath) {
        if (!is_dir($dirpath)) return false;
        $files = glob(rtrim($dirpath, '/\\') . '/*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                dirDelete($file);
            } else {
                @unlink($file);
            }
        }

        return rmdir($dirpath);
    }
}