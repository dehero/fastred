window.intCounter = function() {
    if (typeof(window._intCounter) == 'undefined') {
        window._intCounter = 0;
    }
    return window._intCounter++;
};
window.intFromFloat = function(float) {
    return Math.floor(float);
};
window.intFromHex = function(hex) {
    return parseInt(hex, 16);
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
window.intToHex = function(value, zeroPadding) {
    var result = value.toString(16);
    zeroPadding = parseInt(zeroPadding) || 0;

    return result.length >= zeroPadding
        ?  result
        :  new Array(zeroPadding - result.length + 1).join('0') + result;
};
window.intToStr = function(value, zeroPadding) {
    var result = '' + (varExists(value) ? +value : 0);
    zeroPadding = parseInt(zeroPadding) || 0;

    return result.length >= zeroPadding
        ?  result
        :  new Array(zeroPadding - result.length + 1).join('0') + result;
};