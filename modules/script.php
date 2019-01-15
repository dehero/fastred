<?php

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
