'use strict';

exports.SCRIPT_HOST = window.location.host;

exports.SCRIPT_SCHEME = window.location.protocol + '//';

exports.SCRIPT_ROOT_URL = '/';
//exports.SCRIPT_ROOT_URL = fastredImport('SCRIPT_ROOT_URL');

exports.scriptGetRoute = function() {
    return window.location.pathname.replace(/^\/|\/$/g, '');
};

exports.scriptGetUrl = function(full) {
    if (full) {
        return window.location.href;
    } else {
        return window.location.pathname + window.location.search;
    }
};

exports.scriptSetUrl = function(url) {
    window.history.pushState('', '', url);
}

exports.scriptRedirect = function(url, statusCode) {
    // statusCode is not supported
    window.location.replace(url);
};