<?php

function strRandom($length, $set = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789') {
    fastredRequire('int');

    $result = '';
    $max = strlen($set);

    for ($i = 0; $i < $length; $i++) {
        $result .= $set[intRandom(0, $max - 1)];
    }

    return $result;
}

function strGetNoBOM($str) {
    if (substr($str, 0, 3) == pack('CCC', 0xEF, 0xBB, 0xBF)) {
        $str = substr($str, 3);
    }
    return $str;
}

function strGetCamelCase($str, $delimiter = '-') {
    $parts = explode($delimiter, $str);
    $result = $str;
    if (count($parts) > 0) {
        $result = strtolower($parts[0]);
        for ($i = 1; $i < count($parts); $i++) {
            $result .= ucfirst($parts[$i]);
        }
    }
    return $result;
}

function strGetNoCamelCase($str, $delimiter = '-') {
    $parts = preg_split('/([[:upper:]][[:lower:]]+)/', $str, null, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
    return strtolower(implode($delimiter, $parts));
}

function strGetReplaced($str, $search, $replace) {
    return str_replace($search, $replace, $str);
}

function strGetFormatted($str, $args) {
    if (!is_array($args)) {
        $args = array($args);
    }
    return preg_replace_callback('/%(\d*)/', function($matches) use(&$args) {
        return $args[$matches[1] - 1];
    }, $str);
}

function strGetTrimmed($str, $charlist) {
    return trim($str, $charlist);
}

function strStartsWith($str, $substr) {
	return $substr === '' || strpos($str, $substr) === 0;
}

function strToLowerCase($str) {
    return mb_strtolower($str);
}

function strToUpperCase($str) {
    return mb_strtoupper($str);
}

function strEndsWith($str, $substr) {
	return $substr === '' || substr($str, -strlen($substr)) === $substr;
};