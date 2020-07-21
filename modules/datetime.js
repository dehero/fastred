'use strict';

exports.datetime = function(value) {
    fastredRequire('var');

    if (varIsStr(value)) {
        value = datetimeObj(value);
    }
    if (varIsObj(value)) {
        if (typeof value.toISOString === 'function') {
            var str = value.toISOString();

            return str.substring(0, 10) + ' ' + str.substring(11, 19);            
        } else {
            fastredRequire('int');
            value = datetimeObj(value);

            return intToStr(value.year, 4) + '-' + intToStr(value.month, 2) + '-' + intToStr(value.day, 2) + ' ' +
                   intToStr(value.hour, 2) + ':' + intToStr(value.minute, 2) + ':' + intToStr(value.second, 2);
        }
    } else {
        var date = varIsNumber(value) ? new Date(value) : new Date();
        var str = date.toISOString();

        return str.substring(0, 10) + ' ' + str.substring(11, 19);
    }
};

exports.datetimeGetDate = function(value) {
    return datetime(value).substring(0, 10);
}

exports.datetimeGetTime = function(value) {
    return datetime(value).substring(11, 20);
}

exports.datetimeObj = function(value, month, day, hour, minute, second) {
    fastredRequire('int', 'var');

    var year;
        
    if (varIsObj(value)) {
        year = value.year || 1;
        month = value.month || 1;
        day = value.day || 1;
        hour = value.hour || 0;
        minute = value.minute || 0;
        second = value.second || 0;
    } else if (varIsStr(value)) {
        return datetimeObjFromStr(value);
    } else {
        year = value;
    }
    
    var result = {};

    result.year   = intFromStr(year);
    result.month  = intFromStr(month);
    result.day    = intFromStr(day);
    result.hour   = intFromStr(hour);
    result.minute = intFromStr(minute);
    result.second = intFromStr(second);

    if (result.year < 1)    {result.year = 1}
    if (result.day < 1)     {result.day = 1}
    if (result.month < 1)   {result.month = 1}

    return result;

};

exports.datetimeObjFromStr = function(str) {    
    fastredRequire('var');
    
    var matches;
    var year;
    var month;
    var day;
    var hour;
    var minute;
    var second;

    if (varIsEmpty(str)) {
        return datetimeObj();
    }
    
    if (matches = str.match(/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/)) {
        // 2019-02-19 11:55:05    
        year   = matches[1];
        month  = matches[2];
        day    = matches[3];
        hour   = matches[4];
        minute = matches[5];
        second = matches[6];
    } else if(matches = str.match(/(\d{4})-(\d{2})-(\d{2})/)) {
        // 2019-02-19
        year    = matches[1];
        month   = matches[2];
        day     = matches[3];
    } else if(matches = str.match(/(\d{2}):(\d{2}):(\d{2})/)) {
        // 13:16:19
        hour    = matches[1];
        minute  = matches[2];
        second  = matches[3];
    }

    return datetimeObj(parseInt(year), month, day, hour, minute, second);
};

exports.datetimeObjGetBetween = function(datetime1, datetime2) {
    var obj1 = datetimeObj(datetime1);
    var obj2 = datetimeObj(datetime2 || datetime());

    var date1 = new Date(Date.UTC(obj1.year, obj1.month - 1, obj1.day, obj1.hour, obj1.minute, obj1.second));
    var date2 = new Date(Date.UTC(obj2.year, obj2.month - 1, obj2.day, obj2.hour, obj2.minute, obj2.second));

    if (date1 > date2) {
        var date = date1;
        date1 = date2;
        date2 = date;
    }

    function doCompare(from, end, what) {
        var result = -1;
        while (from <= end) {
            result++;
            from['set' + what](from['get' + what]() + 1);
        }
        from['set' + what](from['get' + what]() - 1);
        return result;
    }

    return {
        year:   doCompare(date1, date2, 'FullYear'),
        month:  doCompare(date1, date2, 'Month'),
        day:    doCompare(date1, date2, 'Date'),
        hour:   doCompare(date1, date2, 'Hours'),
        minute: doCompare(date1, date2, 'Minutes'),
        second: doCompare(date1, date2, 'Seconds')
    };    
};

exports.datetimeGetModified = function(datetime, year, month, day, hour, minute, second) {   
    var obj = datetimeObj(datetime);

    var date = new Date(Date.UTC(obj.year, obj.month - 1, obj.day, obj.hour, obj.minute, obj.second));
    date.setUTCFullYear(obj.year);

    if (varIsNotEmpty(year)) {        
        date.setUTCFullYear(obj.year + year);
    }
    if (varIsNotEmpty(month)) {
        date.setUTCMonth(obj.month - 1 + month);
    }
    if (varIsNotEmpty(day)) {
        date.setUTCDate(obj.day + day);
    }
    if (varIsNotEmpty(hour)) {
        date.setUTCHours(obj.hour + hour);
    }
    if (varIsNotEmpty(minute)) {
        date.setUTCMinutes(obj.minute + minute);
    }
    if (varIsNotEmpty(second)) {
        date.setUTCSeconds(obj.second + second);
    }

    var str = date.toISOString();

    return str.substring(0, 10) + ' ' + str.substring(11, 19);
};

exports.datetimeGetWeekday = function(datetime) {
    var date = new Date(datetime);

    return date.getDay();
};