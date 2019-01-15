<?php

if (!function_exists('json')) {
    function json($value) {
        if (!isset($value)) return 'null';

        return defined('JSON_UNESCAPED_UNICODE')
            ? json_encode($value, JSON_UNESCAPED_UNICODE)
            : preg_replace_callback(
                '/\\\\u([0-9a-f]{4})/i',
                function ($matches) {
                    return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UTF-16');
                },
                json_encode($value));
    }
}