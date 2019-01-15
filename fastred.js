window.fastredLibrary = function(request) {
    if (typeof request === 'function' && typeof request.keys === 'function') {
        request.keys().forEach(request);
    }
};

window.fastredRequire = function() {};

fastredLibrary(require.context('./modules', false, /\.js$/));
require.context('./locales', false, /\.json$/);
