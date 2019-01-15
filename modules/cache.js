window.cache = function(key, func) {
    if (!window._cache) window._cache = {};

    result = window._cache[key];

    if (!varExists(result)) {
        result = func();
        window._cache[key] = result;
    }

    return result;
}