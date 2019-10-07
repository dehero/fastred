<?php

function url($route = null, $get = null, $anchor = null, $full = false, $host = null) {
    fastredRequire('script');

    $result = SCRIPT_ROOT_URL;    

    if ($full) {
        $host = !empty($host) ? $host : SCRIPT_HOST;
        $result = SCRIPT_SCHEME . $host . $result;
    }

    if (!empty($route)) $result .= str_replace('\\', '/', trim($route, '\\\/'));

    if (is_array($get) || is_object($get)) {
        $query = urlGetArrToStr($get);
    } elseif (isset($get)) {
        $query = $get;
    }    
    
    if (!empty($query)) $result .= '?' . $query;    

    if (!empty($anchor)) $result .= '#' . $anchor;

    return $result;
}

function urlGetArrToStr($get) {
    return http_build_query($get);        
}

if (!function_exists('urlFromPath')) {
    function urlFromPath($path, $rootPath = null, $get = null, $anchor = null, $full, $host = false) {
        fastredRequire('script');

        if (is_null($rootPath)) $rootPath = SCRIPT_ROOT_PATH;

        $root = substr($path, 0, strlen($rootPath));
        if ($root != $rootPath) return $path;

        return url(substr($path, strlen($rootPath)), $get, $anchor, $full, $host);
    }
}

// Obsolete
if (!function_exists('urlParamsToGetArr')) {
    /**
     * @param stdClass|array $params
     * @return array
     */
    function urlParamsToGetArr($params, $ctrl = null) {
        if (!is_object($params) && !is_array($params)) return array();

        fastredRequire('str');

        $result = array();
        foreach ($params as $key => $value) {
            $key = strGetNoCamelCase($key, '_');
            $result[$key] = $value;
        }

        return $result;
    }
}

// Obsolete
if (!function_exists('urlParamsFromGetArr')) {
    /**
     * @param array $get
     * @return stdClass
     */
    function urlParamsFromGetArr($get, $ctrl = null) {
        if (!is_array($get)) return new stdClass();

        fastredRequire('str');

        $result = new stdClass();
        foreach ($get as $key => $value) {
            $key = strGetCamelCase($key, '_');
            $result->$key = $value;
        }

        return $result;
    }
}