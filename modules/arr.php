<?php

if (!function_exists('arr')) {
    function arr() {
        return array();
    }
}

if (!function_exists('arrGetCount')) {
    /** 
     * @param array $arr
     * @return int
     */
    function arrGetCount($arr) {
        return count($arr);
    }
}

if (!function_exists('arrGetFiltered')) {
    function arrGetFiltered($arr, $callback = null) {
        // For some reason array_filter($arr, null) IS NOT THE SAME AS array_filter($arr)
        return !is_null($callback) ? array_filter($arr, $callback) : array_filter($arr);
    }
}

if (!function_exists('arrGetFound')) {
    function arrGetFound($arr, $element, $default = null) {
        return in_array($element, $arr) ? $element : $default;
    }
}

if (!function_exists('arrGetMerged')) {
    function arrGetMerged($arr, $merge1) {
        $numArgs = func_num_args();
        for ($i = 1; $i < $numArgs; $i++) {
            $merge = func_get_arg($i);
            foreach ($merge as $value) {
                $arr[] = $value;
            }
        }
        return $arr;
    }
}

if (!function_exists('arrIncludes')) {
    function arrIncludes($arr, $element) {
        return in_array($element, $arr);
    }
}

if (!function_exists('arrFromStr')) {
    function arrFromStr($str, $delimiter) {
        return explode($delimiter, $str);
    }
}

if (!function_exists('arrMerge')) {
    function arrMerge(&$arr, $merge1) {
        $numArgs = func_num_args();
        for ($i = 1; $i < $numArgs; $i++) {
            $merge = func_get_arg($i);
            foreach ($merge as $value) {
                $arr[] = $value;
            }
        }
    }
}

if (!function_exists('arrOfObjGetMapped')) {
    function arrOfObjGetMapped($arr, $mapping) {
        fastredRequire('obj');

        $result = arr();
        if (is_array($arr)) {
            foreach ($arr as $obj) {
                arrPush($result, objGetMapped($obj, $mapping));
            }
        }

        return $result;
    }
}

if (!function_exists('arrOfObjMap')) {
    function arrOfObjMap($arr, $mapping) {
        fastredRequire('obj');

        foreach ($arr as $obj) {
            objMap($obj, $mapping);
        }
    }
}

if (!function_exists('arrPop')) {
    function arrPop(&$arr) {
        return array_pop($arr);
    }
}

if (!function_exists('arrPush')) {
    /**
     * @param array $arr
     * @param mixed $element
     * @return int
     */
    function arrPush(&$arr, $element) {
        return array_push($arr, $element);
    }
}

if (!function_exists('arrShift')) {
    function arrShift(&$arr) {
        return array_shift($arr);
    }
}

if (!function_exists('arrSort')) {
    function arrSort(&$arr, $callback = null) {
        return !is_null($callback) ? usort($arr, $callback) : sort($arr);
    }
}

if (!function_exists('arrToStr')) {
    function arrToStr($arr, $delimiter) {
        return implode($delimiter, $arr);
    }
}