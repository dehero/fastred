<?php

const DATETIME_DEFAULT_TIMEZONE = 'UTC';

date_default_timezone_set(DATETIME_DEFAULT_TIMEZONE);

if (!function_exists('datetime')) {
    /**
     * Creates datetime string value in timezone DATETIME_DEFAULT_TIMEZONE.
     * @param string|DateTime|stdClass|integer|null $value String to parse, DateTime object, simple datetime object or timestamp. If $value is null, it returns current date and time.
     * @return string Datetime in format 'yyyy-mm-dd hh:ii:ss'.
     */
    function datetime($value = null) {
        if (is_string($value)) {
            $value = datetimeObj($value);
        }

        if (is_object($value)) {
            if (is_a($value, 'DateTime')) {
                return $value->format('Y-m-d H:i:s');
            } else {
                fastredRequire('int');
                $value = datetimeObj($value);

                return intToStr($value->year, 4) . '-' . intToStr($value->month, 2) . '-' . intToStr($value->day, 2) . ' ' .
                       intToStr($value->hour, 2) . ':' . intToStr($value->minute, 2) . ':' . intToStr($value->second, 2);
            }
        } else {
            $timestamp = is_integer($value) ? $value : time();

            return strftime('%Y-%m-%d %H:%M:%S', $timestamp);
        }
    }
}

function datetimeGetMonthSize($datetime) {
    return date('t', strtotime($datetime));
}

if (!function_exists('datetimeGetWeekday')) {
    function datetimeGetWeekday($datetime) {
        return date('w', strtotime($datetime));
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

if (!function_exists('datetimeObj')) {
    /**
     * Creates datetime object by parsing a string or by year, month, day, hour, minute and second given separately.
     * @param string|stdClass|integer $arg1 String with date and time or year number.
     * @param integer $month
     * @param integer $day
     * @param integer $hour
     * @param integer $minute
     * @param integer $second
     * @return stdClass Object with datetime components as properties.
     */
    function datetimeObj($value = 1, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0) {
        fastredRequire('obj');

        if (is_object($value) || is_array($value)) {            
            $value = obj($value);

            $year = isset($value->year) ? $value->year : 1;
            $month = isset($value->month) ? $value->month: 1;
            $day = isset($value->day) ? $value->day : 1;
            $hour = isset($value->hour) ? $value->hour : 0;
            $minute = isset($value->minute) ? $value->minute : 0;            
            $second = isset($value->second) ? $value->second : 0;
        } elseif (is_string($value)) {
            return datetimeObjFromStr($value);
        } else {
            $year = $value;
        }

        $result = obj();
        $result->year   = $year > 1 ? (integer)$year : 1;
        $result->month  = $month > 1 ? (integer)$month : 1;
        $result->day    = $day > 1 ? (integer)$day : 1;
        $result->hour   = (integer)$hour;
        $result->minute = (integer)$minute;
        $result->second = (integer)$second;

        return $result;
    }
}

if (!function_exists('datetimeObjFromStr')) {
    /**
     * Creates datetime object by parsing a string.
     * @param string $str String to parse.
     * @return stdClass Object with datetime components as properties.
     */
    function datetimeObjFromStr($str) {
        fastredRequire('obj');

        if (empty($str)) {
            return datetimeObj();
        }        

        $matches = [];
        
        if (preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $str, $matches) === 1) {
            // 2019-02-19 11:55:05
            $year   = $matches[1];
            $month  = $matches[2];
            $day    = $matches[3];
            $hour   = $matches[4];
            $minute = $matches[5];
            $second = $matches[6];
        } elseif(preg_match('/(\d{4})-(\d{2})-(\d{2})/', $str, $matches) === 1) {
            // 2019-02-19
            $year   = $matches[1];
            $month  = $matches[2];
            $day    = $matches[3];
        } elseif(preg_match('/(\d{2}):(\d{2}):(\d{2})/', $str, $matches) === 1) {
            // 13:16:19
            $hour   = $matches[1];
            $minute = $matches[2];
            $second = $matches[3];
        }

        return datetimeObj((integer)$year, $month, $day, $hour, $minute, $second);
    }
}

if (!function_exists('datetimeObjGetBetween')) {
    function datetimeObjGetBetween($datetime1, $datetime2 = null) {
        fastredRequire('obj');

        $d1 = new DateTime($datetime1);
        $d2 = new DateTime($datetime2);

        if ($d1 < $d2) {
            $diff = $d1->diff($d2);
        } else {
            $diff = $d2->diff($d1);
        }
        
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