window.intCounter = function() {
    if (typeof(window._intCounter) == 'undefined') {
        window._intCounter = 0;
    }
    return window._intCounter++;
};
window.intFromFloat = function(float) {
    return Math.floor(float);
};
window.intFromStr = function(str) {
    return +str || 0;
};
window.intIsValid = function(value) {
    if (isNaN(value)) {
        return false;
    }
    var x = parseFloat(value);
    return (x | 0) === x;
};
window.intToStr = function(value, leadingZeros) {
    var result = '' + (varExists(value) ? +value : 0);
    leadingZeros = parseInt(leadingZeros) || 0;

    return result.length >= leadingZeros
        ?  result
        :  new Array(leadingZeros - result.length + 1).join('0') + result;
};
