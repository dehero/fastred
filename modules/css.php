<?php

function cssClassArr($arg1) {
    $args = func_get_args();
    $obj = call_user_func_array('cssClassObj', $args);

    $result = array();

    foreach ($obj as $key => $value) {
        if ($value) {
            $result[] = $key;
        }
    }

    return $result;
}

function cssClassObj($arg1) {
    fastredRequire('obj');

    $args = func_get_args();

    $result = obj();

    foreach ($args as $arg) {
        if (is_object($arg)) {
            objMerge($result, $arg);
        } elseif (is_array($arg)) {
            foreach ($arg as $key => $value) {
                if (is_numeric($key)) {
                    objMerge($result, cssClassObj($value));
                } else {
                    $result->{$key} = $value;
                }
            }
        } elseif (!empty($arg)) {
            $result->{$arg} = true;
        }
    }

    return $result;
}

if (!function_exists('cssClassFromStr')) {
    function cssClassFromStr($str) {
        return trim(preg_replace('/[^A-Za-z0-9-]/', '-', $str), '-');
    }
}

if (!function_exists('cssFromSpriteMap')) {
    function cssFromSpriteMap(&$spriteMap, $spriteFilepath, $prefix = null) {
        fastredRequire('url');

        if (count($spriteMap) == 0) return false;

        if (!empty($prefix)) $prefix = cssClassFromStr($prefix) . '-';
        $prefix = $prefix . cssClassFromStr(urlFromPath($spriteFilepath)) . '-';

        // Defining if all map have same dimentions
        $widthCommon = $spriteMap[0]->width;
        $heightCommon = $spriteMap[0]->height;
        foreach ($spriteMap as $value) {
            if ($value->width != $widthCommon) unset($widthCommon);
            if ($value->height != $heightCommon) unset($heightCommon);
            if (!(isset($widthCommon) || isset($heightCommon))) break;
        }

        $css = '[class^="' . $prefix . '"],[class*="' . $prefix . '"]{' .
            'background-image: url("' . urlFromPath($spriteFilepath) . '");' . (
            isset($widthCommon)
                ? 'width:' . $widthCommon . 'px;'
                : ''
            ) . (
            isset($heightCommon)
                ? 'height:' . $heightCommon . 'px;'
                : ''
            ) . '}' . "\n";


        $i = 0;
        foreach ($spriteMap as $key => $value) {
            if (!is_object($spriteMap[$key])) $spriteMap[$key] = new stdClass();
            $spriteMap[$key]->class = $prefix . sprintf('%0' . strlen(count($spriteMap)) . 's', $i);
            $css .= '.' . $spriteMap[$key]->class . '{' .
                'background-position:' . (0 - $value->offsetX) . 'px ' . (0 - $value->offsetY) . 'px;' . (
                !isset($widthCommon)
                    ? 'width:' . $value->width . 'px;'
                    : ''
                ) . (
                !isset($heightCommon)
                    ? 'height:' . $value->height . 'px;'
                    : ''
                ) . '}' . "\n";
            $i++;
        }

        return $css;
    }
}