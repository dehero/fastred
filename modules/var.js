window.varIsArr = function (value) {
    if (typeof Array.isArray === 'undefined')
        return Object.prototype.toString.call(obj) === '[object Array]';
    else
        return Array.isArray(value);
};

window.varIsNumericArr = window.varIsArr;

window.varIsNotArr = function (value) {
    return !varIsArr(value);
};

window.varExists = function (value) {
    return typeof value !== 'undefined' && value !== null;
};

window.varIsEmpty = function (value) {
    return (typeof value === 'undefined' || value === '' || value === 0 || value === '0' || value === null || value === false || (Array.isArray(value) && value.length === 0 ));
};

window.varIsNotEmpty = function (value) {
    return !varIsEmpty(value);
};

window.varIsHash = function (value) {
    if (value === null) {
        return false;
    }
    return ( (typeof value === 'function') || (typeof value === 'object') );
};

window.varIsNumber = function (value) {
    return !isNaN(value);
};

window.varIsStr = function (value) {
    return (typeof value === 'string') || (value instanceof String);
};

window.varIsObj = function (value) {
    return varIsNotArr(value) && (value !== null) && (typeof(value) === 'object');
}