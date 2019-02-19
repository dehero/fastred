window.datetimeGetModified = function(datetime, year, month, day, hour, minute, second) {
    var date = new Date(datetime);

    if (typeof year !== 'undefined') {
        date.setUTCFullYear(date.getUTCFullYear() + year);
    }
    if (typeof month !== 'undefined') {
        date.setUTCMonth(date.getUTCMonth() + month);
    }
    if (typeof day !== 'undefined') {
        date.setUTCDate(date.getUTCDate() + day);
    }
    if (typeof hour !== 'undefined') {
        date.setUTCHours(date.getUTCHours() + hour);
    }
    if (typeof minute !== 'undefined') {
        date.setUTCMinutes(date.getUTCMinutes() + minute);
    }
    if (typeof second !== 'undefined') {
        date.setUTCSeconds(date.getUTCSeconds() + second);
    }

    var str = date.toISOString();

    return str.substring(0, 10) + ' ' + str.substring(11, 19);
};