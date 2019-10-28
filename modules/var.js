'use strict';

exports.varIsArr = function (value) {
    if (typeof Array.isArray === 'undefined')
        return Object.prototype.toString.call(obj) === '[object Array]';
    else
        return Array.isArray(value);
};

exports.varIsNumericArr = exports.varIsArr;

exports.varIsNotArr = function (value) {
    return !varIsArr(value);
};

exports.varExists = function (value) {
    return typeof value !== 'undefined' && value !== null;
};

exports.varIsEmpty = function (value) {
    return (typeof value === 'undefined' || value === '' || value === 0 || value === '0' || value === null || value === false || (varIsArr(value) && value.length === 0 ));
};

exports.varIsNotEmpty = function (value) {
    return !varIsEmpty(value);
};

exports.varIsHash = function (value) {
    if (value === null) {
        return false;
    }
    return ( (typeof value === 'function') || (typeof value === 'object') );
};

exports.varIsNumber = function (value) {
    return !isNaN(value);
};

exports.varIsStr = function (value) {
    return (typeof value === 'string') || (value instanceof String);
};

exports.varIsObj = function (value) {
    return varIsNotArr(value) && (value !== null) && (typeof(value) === 'object');
};
