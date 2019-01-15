<?php

if (!defined('PAGE_HANDLER_PATH')) {
    fastredRequire('script');
    
    define('PAGE_HANDLER_PATH', SCRIPT_ROOT_PATH);
}


if (!function_exists('page')) {
    function page($params) {

        fastredRequire('obj');

        $result = obj();
        $result->params = obj($params);

        return $result;
    }
}

if (!function_exists('pageBreadcrumb')) {
    function pageBreadcrumb($page, $title, $url) {
        if (!is_array($page->breadcrumbs)) $page->breadcrumbs = array();

        $item = new stdClass();
        $item->title = $title;
        $item->url = $url;
        array_push($page->breadcrumbs, $item);
    }
}


function pageHandler($handler, $function = null) {
    static $handlers;

    if (!is_object($handlers)) {
        fastredRequire('obj');

        $handlers = obj();
    }

    if (is_string($function) || (is_object($function) && ($function instanceof Closure))) {
        $handlers->{$handler} = $function;
    }

    return $handlers->{$handler};
}

function pageHandlerRun($handler, $page) {
    @include_once PAGE_HANDLER_PATH . $handler . '.php';

    $function = pageHandler($handler);
    if (isset($function)) {
        return $function($page);
    }
}

if (!function_exists('pageParamsLoadGetArr')) {
    /**
     * @param array $arr
     * @return stdClass
     */
    function pageParamsLoadGetArr($params, $arr) {
        if (!is_array($arr)) return;

        fastredRequire('str');

        foreach ($arr as $key => $value) {
            $key = strGetCamelCase($key, '_');
            if (!isset($params->$key)) {
                $params->$key = $value;
            }
        }
    }
}

if (!function_exists('pageParamsFromRoute')) {
    /**
     * Check route string is PHP file path in /app/views or app/ctrls
     * @param $route
     * @param $params
     * @return stdClass Object containing page parameters
     */
    function pageParamsFromRoute($route, $host = null) {
        $result = new stdClass();
        $result->route = $route;
        $result->host = $host;

        return $result;
    }
}

if (!function_exists('pageParamsToGetArr')) {
    /**
     * @param stdClass|array $params
     * @return array
     */
    function pageParamsToGetArr($params) {
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

if (!function_exists('pageParamsToRoute')) {
    function pageParamsToRoute($params, &$host = null) {
        fastredRequire('obj');
        
        $params = obj($params);            
            
        $host = $params->host;

        return $params->route;
    }
}

if (!function_exists('pageParamsToUrl')) {
    function pageParamsToUrl($params, $anchor = null, $full = false) {
        fastredRequire('url');

        $route = pageParamsToRoute($params, $host);
        $get = pageParamsToGetArr($params);

        return url($route, $get, $anchor, $full, $host);
    }
}

if (!function_exists('pageHandle')) {
    function pageHandle($page) {
    }
}

if (!function_exists('pageRender')) {
    function pageRender($page) {
        var_dump($page);
    }
}

if (!function_exists('pageRenderToStr')) {
    function pageRenderToStr($page) {
        ob_start();
        pageRender($page);

        return ob_get_clean();
    }
}