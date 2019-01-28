window.objFromArr = function (arr) {
    return arr;
};
window.objGetCopy = function(obj, recursive) {
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
window.objGetMapped = function (obj, mapping) {
    var result = objGetCopy(obj);
    objMap(result, mapping);
    return result;
};
window.objGetMerged = function(obj1, obj2, recursive) {
    var result = objGetCopy(obj1, recursive);

    objMerge(result, obj2);

    return result;
};
window.objGetProperty = function(obj, key, def) {
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
window.objHasProperties = function (obj) {
    for (var name in obj) {
        return false;
    }
    return true;
};
window.objMap = function (obj, mapping) {
    for (key1 in mapping) {
        var key2 = mapping[key1];
        if (key2 && typeof key2 === 'function') {
            obj[key1] = key2(obj);
        } else {
            obj[key1] = obj[key2];
        }
        
    }
};
window.objMerge = function(obj1, obj2, recursive) {
    if (!varIsObj(obj1) || !varIsObj(obj2)) return null;

    for(var key in obj2) {
        value = obj2[key];
        if (recursive && varIsObj(value) && varIsObj(obj1[key])) {
            objMerge(obj1[key], value, recursive);
        } else {
            obj1[key] = value;
        }
    }
};
