<?php

const DATETIME_DEFAULT_TIMEZONE = 'UTC';

date_default_timezone_set(DATETIME_DEFAULT_TIMEZONE);

if (!function_exists('datetime')) {
    function datetime($value = null) {
        if (is_object($value)) {
            return $value->year . '-' . $value->month . '-' . $value->day . ' ' .
                $value->hour . ':' . $value->minute . ':' . $value->second;
        } else {
            $timestamp = is_null($value) ? time() : $value;

            return strftime('%Y-%m-%d %H:%M:%S', $timestamp);
        }
    }
}

function datetimeObj($arg1 = null) {
    if (is_object($arg1)) return $arg1;

    fastredRequire('obj');

    $result = obj();
    $numArgs = func_num_args();

    if ($numArgs > 1) {
        for ($i = 0; $i < 6; $i++) {
            $value = null;
            if ($i < $numArgs) {
                $value = func_get_arg($i);
            }
            switch ($i) {
                case 0: $result->year   = $value; break;
                case 1: $result->month  = $value; break;
                case 2: $result->day    = $value; break;
                case 3: $result->hour   = $value; break;
                case 4: $result->minute = $value; break;
                case 5: $result->second = $value; break;
            }
        }

    } else {
        $datetime = is_null($arg1) ? datetime() : $arg1;
        $parts = preg_split('/[- :]/', $datetime);

        $result->year   = $parts[0];
        $result->month  = $parts[1];
        $result->day    = $parts[2];
        $result->hour   = $parts[3];
        $result->minute = $parts[4];
        $result->second = $parts[5];
    }

    return $result;
}

function datetimeObjGetBetween($datetime1, $datetime2 = null) {
    fastredRequire('obj');

    $d1 = new DateTime($datetime1);
    $d2 = new DateTime($datetime2);

    $diff = $d2->diff($d1);
    $result = obj();

    $result->year   = $diff->y;
    $result->month  = $diff->m;
    $result->day    = $diff->d;
    $result->hour   = $diff->h;
    $result->minute = $diff->i;
    $result->second = $diff->s;

    return $result;
}