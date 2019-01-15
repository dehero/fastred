<?php

function cache($key, $function) {
    static $cache;

    if (!isset($cache)) $cache = new stdClass();
    
    $result = @$cache->$key;
    if (!isset($result)) {
        $result = $function();
        $cache->$key = $result;
    }
    
    return $result;    
}

function cacheClear() {
}