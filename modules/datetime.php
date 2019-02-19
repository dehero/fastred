<?php

const DATETIME_DEFAULT_TIMEZONE = 'UTC';

date_default_timezone_set(DATETIME_DEFAULT_TIMEZONE);

if (!function_exists('datetime')) {
    function datetime($value = null) {
        if (is_string($value)) {
            $value = datetimeObj($value);
        }
        if (is_object($value)) {
            if (is_a($value, 'DateTime')) {
                return $value->format('Y-m-d H:i:s');
            } else {
                fastredRequire('int');

                return intToStr($value->year, 4) . '-' . intToStr($value->month, 2) . '-' . intToStr($value->day, 2) . ' ' .
                       intToStr($value->hour, 2) . ':' . intToStr($value->minute, 2) . ':' . intToStr($value->second, 2);
            }
        } else {
            $timestamp = is_null($value) ? time() : $value;

            return strftime('%Y-%m-%d %H:%M:%S', $timestamp);
        }
    }
}

function datetimeObjFromStr($str) {
    fastredRequire('obj');

    $result = obj();
    $result->year   = 0;
    $result->month  = 0;
    $result->day    = 0;
    $result->hour   = 0;
    $result->minute = 0;
    $result->second = 0;

    $matches = [];
    
    if (preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $str, $matches) === 1) {
        // 2019-02-19 11:55:05    
        $result->year   = (integer)$matches[1];
        $result->month  = (integer)$matches[2];
        $result->day    = (integer)$matches[3];
        $result->hour   = (integer)$matches[4];
        $result->minute = (integer)$matches[5];
        $result->second = (integer)$matches[6];
    } elseif(preg_match('/(\d{4})-(\d{2})-(\d{2})/', $str, $matches) === 1) {
        // 2019-02-19
        $result->year   = (integer)$matches[1];
        $result->month  = (integer)$matches[2];
        $result->day    = (integer)$matches[3];
    } elseif(preg_match('/(\d{2}):(\d{2}):(\d{2})/', $str, $matches) === 1) {
        // 13:16:19
        $result->hour    = (integer)$matches[1];
        $result->minute  = (integer)$matches[2];
        $result->second  = (integer)$matches[3];
    }

    return $result;
}


function datetimeGetMonthSize($datetime) {
    return date('t', strtotime($datetime));
}

if (!function_exists('datetimeGetWeekday')) {
    function datetimeGetWeekday($datetime) {
        return date('w', strtotime($datetime));
    }
}

if (!function_exists('datetimeObj')) {
    function datetimeObj($arg1 = null) {
        if (is_object($arg1)) return $arg1;

        fastredRequire('arr', 'obj');

        $result = obj();
        $numArgs = func_num_args();

        if ($numArgs > 1) {
            for ($i = 0; $i < 6; $i++) {
                $value = null;
                if ($i < $numArgs) {
                    $value = func_get_arg($i);
                }
                switch ($i) {
                    case 0: $result->year   = (integer)$value; break;
                    case 1: $result->month  = (integer)$value; break;
                    case 2: $result->day    = (integer)$value; break;
                    case 3: $result->hour   = (integer)$value; break;
                    case 4: $result->minute = (integer)$value; break;
                    case 5: $result->second = (integer)$value; break;
                }
            }

        } elseif (is_null($arg1)) {
            // $result->year   = (integer)$parts[0];
            // $result->month  = (integer)$parts[1];
            // $result->day    = (integer)$parts[2];
            // $result->hour   = (integer)$parts[3];
            // $result->minute = (integer)$parts[4];
            // $result->second = (integer)$parts[5];
        } else {
            // $datetime = is_null($arg1) ? datetime() : $arg1;

            $result = datetimeObjFromStr($arg1);
        }

        return $result;
    }
}

if (!function_exists('datetimeGetModified')) {
    function datetimeGetModified($datetime, $year = 0, $month = 0, $day = 0, $hour = 0, $minute = 0, $second = 0) {
        $d = new DateTime($datetime);
        
        $year = $year ? $year : 0;
        $month = $month ? $month : 0;
        $day = $day ? $day : 0;
        $hour = $hour ? $hour : 0;
        $minute = $minute ? $minute : 0;
        $second = $second ? $second : 0;

        $d->modify("{$year} year {$month} month {$day} day {$hour} hour {$minute} minute {$second} second");

        return datetime($d);
    }
}

if (!function_exists('datetimeObjGetBetween')) {
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
}