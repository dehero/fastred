'use strict';

exports.obj = function(obj) {
    fastredRequire('var');

    if (varIsObj(obj)) {
        return obj;        
    } else if (varIsArr(obj)) {
        return objFromArr(obj);
    } else {
        return {};
    }   
};

exports.objFromArr = function (arr) {
    fastredRequire('var');

    var result = {};
    for (var i = 0; i < arr.length; ++i) {
        var value = arr[i];
        result[i] = varIsArr(value) ? objFromArr(value) : value;
    }
    return result;
};

exports.objGetCopy = function(obj, recursive) {
    fastredRequire('var');

    if (!varIsObj(obj) && !varIsArr(obj)) return obj;

    var result = {};
    for(var key in obj) {
        var value = obj[key];
        if (recursive && varIsObj(value)) {
            result[key] = objGetCopy(value, recursive);
        } else {
            result[key] = value;
        }
    }
    return result;
};

exports.objGetMapped = function (obj, mapping) {
    var result = objGetCopy(obj);
    objMap(result, mapping);
    return result;
};

exports.objGetMerged = function(obj1, obj2, recursive) {
    var result = objGetCopy(obj1, recursive);

    objMerge(result, obj2);

    return result;
};

exports.objGetProperty = function(obj, key, def) {
    fastredRequire('var');

    var result;
    if (varIsObj(obj) && key != '') {
        result = obj[key];
    } 
    if (typeof result === 'undefined') {
        if (def && typeof def === 'function') {
            return def();
        } else {
            return def;
        }
    };
    return result;
};

exports.objHasProperties = function (obj) {
    for (var name in obj) {
        return false;
    }
    return true;
};

exports.objMap = function (obj, mapping) {
    for (var key1 in mapping) {
        var key2 = mapping[key1];
        if (key2 && typeof key2 === 'function') {
            obj[key1] = key2(obj);
        } else {
            obj[key1] = obj[key2];
        }        
    }
};

exports.objMerge = function(obj1, obj2, recursive) {
    fastredRequire('var');

    if (!varIsObj(obj1) || !varIsObj(obj2)) return null;

    for(var key in obj2) {
        var value = obj2[key];
        if (recursive && varIsObj(value) && varIsObj(obj1[key])) {
            objMerge(obj1[key], value, recursive);
        } else {
            obj1[key] = value;
        }
    }
};
