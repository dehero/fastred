<?php

function path($arg1) {
    fastredRequire('arr');

    $pieces = [];
    $fromRoot = false;

    $numArgs = func_num_args();
    for ($i = 0; $i < $numArgs; $i++) {
        $arg = func_get_arg($i);
        $fromRoot = $fromRoot || (pieces.length === 0 && preg_match('/^[\\/]/', $arg));

        $arr = pathToArr($arg);
        arrMerge($pieces, $arr);
    }

    if ($fromRoot) {
        array_unshift($pieces, '');
    }

    return pathFromArr($pieces);
}

function pathFromArr($arr) {
    return implode('/', $arr);
}

function pathToArr($path) {
    return preg_split( "_[\\\\/]_", $path, -1, PREG_SPLIT_NO_EMPTY);
}

function pathToIdent($path) {
    return trim(preg_replace('/[^A-Za-z0-9-.]/', '-', $path), '-');
}

/*
function pathToInfo($path) {
    fastredUse('obj');

    $info = pathinfo($path);

    $result = obj();
    $result->ext = $info['extension'];
    $result->dir = $info['directory'];
    $result->fullname = $info['basename'];
    $result->name = $info['filename'];

    return $result;
}

function pathFromInfo($info, $separator = DIRECTORY_SEPARATOR) {


    return $result;
}
*/

function pathGetExt($path) {
    $parts = explode('.', $path);
    if (count($parts) > 1) {
        return array_pop($parts);
    }

    return '';
}

function pathGetWithNewExt($path, $ext) {
    $parts = explode('.', $path);

    if (count($parts) > 1) {
        array_pop($parts);
    }

    if (!empty($ext)) {
        $parts[] = $ext;
    }

    return implode('.', $parts);
}

function pathGetWithPostfix($path, $postfix) {
    if (empty($postfix)) return $path;
    $parts = explode('.', $path);
    $path = '';
    for ($i = 0; $i < count($parts); $i++) {
        $path .= ($i > 0 ? '.' : '') . ($i == count($parts) - 1 ? $postfix . '.' : '') . $parts[$i];
    }

    return $path;
}

function pathGetWithPrefix($path, $prefix) {
    if (empty($prefix)) return $path;

    return dirname($path) . '/' . $prefix . '.' . ltrim(basename($path), '.');
}