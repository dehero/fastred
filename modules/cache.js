'use strict';

exports.cache = function(key, func) {
    fastredRequire('var');
    
    if (!exports._cache) exports._cache = {};

    var result = exports._cache[key];

    if (!varExists(result)) {
        result = func();
        exports._cache[key] = result;
    }

    return result;
};