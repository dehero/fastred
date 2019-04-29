<?php

/**
 * @return int
 */
function intCounter() {
    static $counter = 0;
    return $counter++;
}

function intRandom($min, $max) {
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int)($log / 8) + 1; // length in bytes
    $bits = (int)$log + 1; // length in bits
    $filter = (int)(1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);

    return $min + $rnd;
}

function intFromFloat($float) {
    return intval($float);
}

function intFromHex($hex) {
    return hexdec($hex);
}

function intFromStr($str) {
    return intval($str);
}

function intIsValid($value) {
    return is_int($value);
}

function intToHex($value, $zeroPadding = null) {
    $result = dechex($value);

    return !empty($zeroPadding)
        ? str_pad($result, $zeroPadding, '0', STR_PAD_LEFT)
        : $result;
}

function intToStr($value, $zeroPadding = null) {
    $result = (string)(integer)$value;

    return !empty($zeroPadding)
        ? str_pad($result, $zeroPadding, '0', STR_PAD_LEFT)
        : $result;
};