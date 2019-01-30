<?php

function obj($obj = null) {
    if (is_object($obj)) {
        return $obj;        
    } else if (is_array($obj)) {
        return objFromArr($obj);
    } else {
        return new stdClass();
    }    
}

/**
 * Puts all object or array properties into new object
 * @param stdClass|array $obj
 * Object or array to get copy from.
 * @param bool $recursive
 * If true, the function creates copies of all child objects, otherwise copies their references.
 * @return stdClass
 */
function objGetCopy($obj, $recursive = false) {
    if (!is_object($obj) && !is_array($obj)) return $obj;
    
    $result = obj();
    foreach ($obj as $key => $value) {
        if ($recursive && is_object($value)) {
            $result->$key = objGetCopy($value, $recursive);
        } else {
            $result->$key = $value;
        }
    }
    return $result;
}

function objGetMapped($obj, $mapping) {
    $result = objGetCopy($obj);
    objMap($result, $mapping);
    return $result;
}

function objMap($obj, $mapping) {
    foreach ($mapping as $key1 => $key2) {
        if (is_object($key2) && ($key2 instanceof Closure)) {
            $obj->{$key1} = $key2($obj);
        } else {
            $obj->{$key1} = $obj->{$key2};
        }
    }
}

function objGetMerged($obj1, $obj2, $recursive = false) {
    $result = objGetCopy($obj1, $recursive);

    objMerge($result, $obj2);

    return $result;
}

function objGetProperty($obj, $key, $default = null) {
    if ($key != '') {
        if (is_object($obj)) {
            $result = $obj->{$key};
        } elseif (is_array($obj)) {
            $result = $obj[$key];
        }
    } 
    if (!isset($result)) {
        if (is_object($default) && ($default instanceof Closure)) {
            return $default();
        } else {
            return $default;
        }
    };
    return $result;
}

function objGetRestricted($obj, $keys, $mode = 'allowed') {
    $newObj = obj();

    if ($mode == 'allowed') {
        for ($i = 0; $i < count($keys); $i++) {
            $key = $keys[$i];
            $newObj->$key = $obj->$key;
        }
    } else {
        foreach ($obj as $key => $value) {
            if (!in_)

            $newObj->$key = $value;
        }
    }

    return $newObj;
}

function objFromArr($arr) {
    $obj = new stdClass();
    foreach ($arr as $key => $value) {
        $obj->$key = is_array($value) ? objFromArr($value) : $value;
    }
    return $obj;
}

if (!function_exists('objFromIni')) {
    function objFromIni($ini) {
        return objFromArr(parse_ini_string($ini, null, INI_SCANNER_RAW));
    }
}

if (!function_exists('objFromJsonFile')) {
    function objFromJsonFile($filename) {
        $result = @json_decode(@file_get_contents($filename));
        if (!is_object($result)) {
            $result = obj();
        }

        return $result;
    }
}

if (!function_exists('objFromJsonFileCached')) {
    function objFromJsonFileCached($filename) {
        fastredRequire('cache');

        return cache('objFromJsonFileCached!' . $filename, function () use ($filename) {
            return objFromJsonFile($filename);
        });
    }
}

if (!function_exists('objToJsonFile')) {
    function objToJsonFile($obj, $filename) {
        fastredRequire('json');

        @file_put_contents($filename, json($obj));
    }
}

function objHasProperties($obj) {
    return objGetCount($obj) > 0;
}

function objGetCount($obj) {
    return count((array)($obj));
}

/**
 * Put all properties of second object or array into first object
 * @param object $obj1
 * Object to merge properties into.
 * @param object|array $obj2
 * Object or array to get properties being merged from.
 * @param bool $recursive
 * If true, the function also merges all child objects, otherwise merges their references.
 * @return null
 */
function objMerge($obj1, $obj2, $recursive = false) {
    if ((!is_object($obj2) && !is_array($obj2))) return null;
    foreach($obj2 as $key => $value) {
        if ($recursive && is_object($value) && is_object($obj1->$key)) {
            objMerge($obj1->$key, $value, $recursive);
        } else {
            $obj1->$key = $value;
        }
    }
}

function objSetProperty(&$obj, $key, $value) {
    if (is_object($obj)) {
        $obj->$key = $value;
    } elseif (is_array($obj)) {
        $obj[$key] = $value;
    }
}

function objToArr($obj) {
    $arr = array();
    foreach ($obj as $key => $value) {
        $arr[$key] = is_object($value) ? objToArr($value) : $value;
    }
    return $arr;
}

function objToArrOfObj($obj, $keyProperty = 'key', $valueProperty = 'value') {
    $arr = array();
    foreach ($obj as $key => $value) {
        $item = obj();
        $item->{$keyProperty} = $key;
        $item->{$valueProperty} = $value;
        array_push($arr, $item);
    }
    return $arr;
}

if (!function_exists('objToIni')) {
    function objToIni($obj) {
        $result = '';
        foreach ($obj as $key => $value) {
            $result .= "$key=$value\n";
        }

        return $result;
    }
}

function objToJson($obj) {
    fastredRequire('json');
    return json($obj);
}
