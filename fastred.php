<?php
/**
 * Fastred
 * @author Dehero <dehero@outlook.com>
 */

const FASTRED_LIBRARY_PATH = __DIR__ . '/modules';

$__FASTRED_LIBRARIES = array();

/**
 * Adds new search path to the end of list used for requiring fastred modules.
 * @param $path string Path to add
 */
function fastredLibrary($path) {
    global $__FASTRED_LIBRARIES;

    $__FASTRED_LIBRARIES[] = realpath($path);
}

/**
 * Requires an unlimited list of fastred modules from directories added by fastredLibrary function
 * @static $requires array Used to store required modules, and then check them with isset() function to require once
 * @link https://robert.accettura.com/blog/2011/06/11/phps-include_once-is-insanely-expensive/
 * @param string $arg1,... List of modules to require
 */
function fastredRequire($arg1) {
    global $__FASTRED_LIBRARIES;
    static $requires = array();

    $numArgs = func_num_args();
    for ($i = 0; $i < $numArgs; $i++) {
        $name = func_get_arg($i);
        if (!isset($requires[$name])) {
            $pathCount = count($__FASTRED_LIBRARIES);
            for ($j = $pathCount - 1; $j > -1; $j--) {
                $filename = $__FASTRED_LIBRARIES[$j] . '/' . $name . '.php';
                if (file_exists($filename)) require $filename;
            }
            $requires[$name] = true;
        }
    }
}

function fastredHandleFatalError() {
    $error = error_get_last();
    if ($error['type'] === E_ERROR) {
        // fatal error has occured
        flush();
    }
};

register_shutdown_function('fastredHandleFatalError');

// Force set every Fastred script encoding to UTF-8 to avoid bugs on different servers
mb_internal_encoding('UTF-8');

fastredLibrary(FASTRED_LIBRARY_PATH);
