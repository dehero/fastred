'use strict';

exports.cssClassArr = function() {
    var obj = cssClassObj.apply(this, arguments);
    var result = [];

    for(var key in obj) {
        if (obj[key]) {
            result.push(key);
        }
    }

    return result;
};

exports.cssClassObj = function() {
    fastredRequire('obj', 'var');

    var result = {};
    var arg;

    for (var i = 0; i < arguments.length; i++) {
        arg = arguments[i];

        if (varIsObj(arg)) {
            objMerge(result, arg);
        } else if (varIsArr(arg)) {
            for (var j = 0; j < arg.length; j++) {
                objMerge(result, cssClassObj(arg[j]));
            }
        } else if (varIsNotEmpty(arg)) {
            result[arg] = true;
        }
    }

    return result;
};