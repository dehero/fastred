window.floatGetCeil = function(float) {
    return Math.ceil(float);
};

window.floatGetFloor = function(float) {
    return Math.floor(float);
};

window.floatGetRound = function(float) {
    return Math.round(float);
};

window.floatToStr = function(float, precision) {
    return float.toFixed(precision);
};
