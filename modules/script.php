<?php

if (!defined('SCRIPT_HOST')) {
    define('SCRIPT_HOST', empty($_SERVER['HTTP_HOST']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST']);
}

if (!defined('SCRIPT_ROOT_URL')) {
    $dir = trim(dirname($_SERVER['SCRIPT_NAME']), '\\/');
    /**
     * @var string Folder that contains initial application PHP file relatively to server root 
     */
    define('SCRIPT_ROOT_URL', '/' . $dir . (!empty($dir) ? '/' : ''));
}

if (!defined('SCRIPT_ROOT_PATH')) {
    /**
     * @var string Full physical path to initial PHP script working folder
     */
    define('SCRIPT_ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . SCRIPT_ROOT_URL);
}

if (!defined('SCRIPT_SCHEME')) {
    define('SCRIPT_SCHEME', (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://');
}

if (!function_exists('scriptGetRoute')) {
    /**
     * Return relative path to current page from site root, excluding parameter string.
     * @return string
     */
    function scriptGetRoute() {
        if (!isset($_SERVER['PATH_INFO'])) {
            $lastSlash = strrpos($_SERVER['SCRIPT_NAME'], '/');
            $currentPath = current(explode('?', $_SERVER['REQUEST_URI'], 2));
            return $lastSlash === 0 ? substr($currentPath, 1) : substr($currentPath, $lastSlash);
        } else {
            return substr($_SERVER['PATH_INFO'], 1);
        }
    }
}

if (!function_exists('scriptGetUrl')) {
    function scriptGetUrl($full = false) {
        $result = $_SERVER['REQUEST_URI'];
        if ($full) $result = SCRIPT_SCHEME . SCRIPT_HOST . $result;
        
        return $result;
    }
}

if (!function_exists('scriptRedirect')) {
    function scriptRedirect($url, $statusCode = 303) {
        header('Location: ' . $url, true, $statusCode);
        die();
    }
}