<?php

fastredRequire('script');

if (!defined('APP_PATH')) {
    define('APP_PATH', SCRIPT_ROOT_PATH . 'app/');
}

if (!defined('APP_CACHE_PATH')) {
    define('APP_CACHE_PATH', APP_PATH . 'cache/');
}

if (!function_exists('app')) {
    function app() {
        fastredRequire('cache');

        return cache('app', function() {
            $app = new stdClass();

            $app->debug = true;

            return $app;
        });
    }
}

if (!function_exists('appErrorHandle')) {
    function appErrorHandle() {
        try {
            appError(app()->statusCode);
        } catch (Exception $e) {
            $app = app();

            if (empty($app->statusCode) || $app->statusCode < 400) $app->statusCode = 500;

            appStatusCodeHeader($app->statusCode);
            echo "<html><head></head><body><h1>HTTP Error $app->statusCode</h1><pre>$app->debugOutput</pre><address></body></html>";
            die();
        }
    }
}

if (!function_exists('appCtrlToRoute')) {
    function appCtrlToRoute($ctrl, &$get, &$host) {
        if ($ctrl == 'index') return '';

        return $ctrl;
    }
}

if (!function_exists('appError')) {
    function appError($errorCode = 404, $message = '') {
        $app = app();
        $app->statusCode = $errorCode;
        throw new Exception($message, $errorCode);
    }
}

if (!function_exists('appRouteToCtrl')) {
    /**
     * Check route string is PHP file path in /app/views or app/ctrls
     * @param $route
     * @param $params
     * @return null|string
     */
    function appRouteToCtrl($route, &$get, $host) {
        if (empty($route)) return 'index';

        return $route;
    }
}

if (!function_exists('appRun')) {
    function appRun() {
        try {
            ob_start();

            $app = app();

            $app->startTime = microtime(true);

            // Get initial route and exclude starting and ending slashes
            $route = trim(scriptGetRoute(), '/');

            fastredRequire('page');

            $params = pageParamsFromRoute($route, $_SERVER['HTTP_HOST']);

            // Deny accessing route, if it doesn't turn to controller
            if (is_null($params)) appError(404);

            pageParamsLoadGetArr($params, $_GET);

            $app->page = page($params);

            $statusCode = pageHandle($app->page);

            $app->debugOutput = ob_get_clean();

            // Check return value of page handler for being error code
            if (appStatusCodeIsError($statusCode)) {
                appError($statusCode);
            }

            if (!$app->debug) ini_set('display_errors', 0);

            pageRender($app->page);

        } catch (Exception $e) {
            $app = app();
            $app->debugOutput = ob_get_clean();
            appErrorHandle();
        }
    }
}

function appStatusCodeHeader($statusCode) {
    @header(' ', true, $statusCode);
}

function appStatusCodeIsError($statusCode) {
    return is_integer($statusCode) && $statusCode >= 400;
}