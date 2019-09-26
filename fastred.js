'use strict';

var global = typeof global !== 'undefined' 
    ? global 
    : typeof window !== 'undefined'
        ? window
        : {};

global.__FASTRED_REQUIRES = {};
global.__FASTRED_LIBRARIES = [];

global.fastredLibrary = function(request) {
    if (typeof request === 'function' && typeof request.keys === 'function') {
        var keys = request.keys();
        var numKeys = keys.length;
        var modules = {};

        for (var i = 0; i < numKeys; i++) {
            var filename = keys[i];
            modules[filename] = request(filename);
        }

        global.__FASTRED_LIBRARIES.push(modules);
    }
};

global.fastredRequire = function(arg1) {
    var numArgs = arguments.length;

    for (var i = 0; i < numArgs; i++) {
        var name = arguments[i];

        if (!global.__FASTRED_REQUIRES[name]) {
            var pathCount = global.__FASTRED_LIBRARIES.length;

            for (var j = pathCount - 1; j > -1; j--) {
                var filename = './' + name + '.js';
                var modules = global.__FASTRED_LIBRARIES[j];
                var module = modules[filename];

                if (module) {
                    for(var key in module) {
                        window[key] = module[key];
                    }
                }
            }
            global.__FASTRED_REQUIRES[name] = true;
        }
    }    
};

fastredLibrary(require.context('./modules', false, /\.js$/));
require.context('./locales', false, /\.json$/);