window.URL_HOST = window.location.host;

window.URL_SCHEME = window.location.protocol + '//';

window.url = function(route, getArg, anchor, full, host) {
    fastredRequire('script', 'var');

    var
        result = SCRIPT_ROOT_URL,
        query = null;

    if (full) {
        host = varIsNotEmpty(host) ? host : URL_HOST;
        result = URL_SCHEME . host . result;
    }

    if (varIsNotEmpty(route)) result += route;

    if (varIsHash(getArg)) {
        query = urlGetArrToStr(getArg);
    } else if (varExists(getArg)) {
        query = getArg;
    }

    if (varIsNotEmpty(query)) result += '?' + query;

    if (varIsNotEmpty(anchor)) result += '#' + anchor;

    return result;
};

window.urlGetArrToStr = function(getArr) {
    
    var urlencode = function urlencode(str) {
        str = str + '';

        return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
    };

    var value;
    var key;
    var tmp = [];
    var argSeparator = '&'

    var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) {
        return typeof obj;
    } : function (obj) {
        return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };

    var _httpBuildQueryHelper = function _httpBuildQueryHelper(key, val, argSeparator) {
        var k;
        var tmp = [];
        if (val === true) {
            val = '1';
        } else if (val === false) {
            val = '0';
        }
        if (val !== null) {
            if ((typeof val === 'undefined' ? 'undefined' : _typeof(val)) === 'object') {
                for (k in val) {
                    if (val[k] !== null) {
                        tmp.push(_httpBuildQueryHelper(key + '[' + k + ']', val[k], argSeparator));
                    }
                }
                return tmp.join(argSeparator);
            } else if (typeof val !== 'function') {
                return urlencode(key) + '=' + urlencode(val);
            } else {
                throw new Error('There was an error processing for http_build_query().');
            }
        } else {
            return '';
        }
    };

    for (key in getArr) {
        value = getArr[key];

        var query = _httpBuildQueryHelper(key, value, argSeparator);
        if (query !== '') {
            tmp.push(query);
        }
    }

    return tmp.join(argSeparator);
};