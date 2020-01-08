'use strict';

exports.floatGetCeil = function(float) {
    return Math.ceil(float);
};

exports.floatGetFloor = function(float) {
    return Math.floor(float);
};

exports.floatGetRound = function(float) {
    return Math.round(float);
};

exports.floatToStr = function(float, precision) {
    precision  = typeof precision !== 'undefined' ? precision : 2;
    
    return float.toFixed(precision);
};
