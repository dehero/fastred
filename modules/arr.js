'use strict';

exports.arr = function() {
    return [];
};

exports.arrGetCount = function (arr) {
    return varIsArr(arr) ? arr.length : 0;
};

exports.arrIncludes = function (arr, element) {
    return arr.indexOf(element) > -1;
};

exports.arrGetFound = function(arr, element, def) {
    return arr.indexOf(element) > -1 ? element : def;
};

exports.arrGetFiltered = function(arr, callback) {
    var result = [];

    callback = callback || function (element) {
        return element;
    };

    for (var key in arr) {
        if (callback(arr[key])) {
            result[key] = arr[key];
        }
    }

    return result;
};

exports.arrMerge = function(arr) {
    for (var i = 1, numArgs = arguments.length; i < numArgs; i++) {
        var merge = arguments[i];
        for(var j = 0, l = merge.length; j < l; j++) {
            arr.push(merge[j]);
        }
    }
};

exports.arrFromStr = function(str, delimiter) {
    if (typeof str !== 'string') return [];
    return str.split(delimiter);
};

exports.arrOfObjGetMapped = function (arr, mapping) {
    fastredRequire('obj', 'var');

    var result = [];
    if (varIsArr(arr)) {
        for (var i = 0, count = arr.length; i < count; i++) {
            var obj = arr[i];
            arrPush(result, objGetMapped(obj, mapping));
        }
    }

    return result;
};

exports.arrOfObjMap = function (arr, mapping) {
    fastredRequire('obj');

    for (var i = 0, count = arr.length; i < count; i++) {
        var obj = arr[i];
        objMap(obj, mapping);
    }
};

exports.arrPop = function (arr) {
    return arr.pop();
};

exports.arrPush = function (arr, element) {
    return arr.push(element);
};

exports.arrShift = function (arr) {
    return arr.shift();
};

exports.arrSort = function(arr, callback) {
    return arr.sort(callback);
};

exports.arrToStr = function(arr, delimiter) {
    return arr.join(delimiter);
};