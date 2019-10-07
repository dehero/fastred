'use strict';

exports.intCounter = function() {
    if (typeof(exports._intCounter) == 'undefined') {
        exports._intCounter = 0;
    }
    return exports._intCounter++;
};

exports.intFromFloat = function(float) {
    return Math.floor(float);
};

exports.intFromHex = function(hex) {
    return parseInt(hex, 16);
};

exports.intFromStr = function(str) {
    return +str || 0;
};

exports.intIsValid = function(value) {
    if (isNaN(value)) {
        return false;
    }
    var x = parseFloat(value);
    return (x | 0) === x;
};

exports.intToHex = function(value, zeroPadding) {
    var result = value.toString(16);
    zeroPadding = parseInt(zeroPadding) || 0;

    return result.length >= zeroPadding
        ?  result
        :  new Array(zeroPadding - result.length + 1).join('0') + result;
};

exports.intToStr = function(value, zeroPadding) {
    fastredRequire('var');
    
    var result = '' + (varExists(value) ? +value : 0);
    zeroPadding = parseInt(zeroPadding) || 0;

    return result.length >= zeroPadding
        ?  result
        :  new Array(zeroPadding - result.length + 1).join('0') + result;
};