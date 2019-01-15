window.arr = function() {
    return [];
}
window.arrGetCount = function (arr) {
    return varIsArr(arr) ? arr.length : 0;
};
window.arrIncludes = function (arr, element) {
    return arr.indexOf(element) > -1;
};
window.arrGetFound = function(arr, element, def) {
    return arr.indexOf(element) > -1 ? element : def;
};
window.arrGetFiltered = function(arr, callback) {
    var result = [], key;

    callback = callback || function (element) {
        return element;
    };

    for (key in arr) {
        if (callback(arr[key])) {
            result[key] = arr[key];
        }
    }

    return result;
};
window.arrMerge = function(arr) {
    for (var i = 1, numArgs = arguments.length; i < numArgs; i++) {
        var merge = arguments[i];
        for(var j = 0, l = merge.length; j < l; j++) {
            arr.push(merge[j]);
        }
    }
};
window.arrFromStr = function(str, delimiter) {
    if (typeof str !== 'string') return [];
    return str.split(delimiter);
};
window.arrOfObjGetMapped = function (arr, mapping) {
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
window.arrOfObjMap = function (arr, mapping) {
    fastredRequire('obj');

    for (var i = 0, count = arr.length; i < count; i++) {
        var obj = arr[i];
        objMap(obj, mapping);
    }
};
window.arrPop = function (arr) {
    return arr.pop();
};
window.arrPush = function (arr, element) {
    return arr.push(element);
};
window.arrShift = function (arr) {
    return arr.shift();
};
window.arrToStr = function(arr, delimiter) {
    return arr.join(delimiter);
};