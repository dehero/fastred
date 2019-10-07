'use strict';

exports.path = function () {
    fastredRequire('arr');

    var pieces = [];

    for (var i = 0, numArgs = arguments.length; i < numArgs; i++) {
        arr = pathToArr(arguments[i]);
        arrMerge(pieces, arr);
    }

    return pathFromArr(pieces);
};

exports.pathFromArr = function (arr) {
    return arr.join('/');
};

exports.pathToArr = function (path) {
    path += '';
    return path.split(/[\\\/]/).filter(function (n) {
        return n !== ''
    });
};

exports.pathGetExt = function(path) {
    fastredRequire('arr');

    var parts = arrFromStr(path, '.');

    if (arrGetCount(parts) > 1) {
        return arrPop(parts);
    }

    return '';
};

exports.pathGetWithNewExt = function(path, ext) {
    fastredRequire('arr');

    var parts = arrFromStr(path, '.');

    if (arrGetCount(parts) > 1) {
        arrPop(parts);
    }

    if (varIsNotEmpty(ext)) {
        arrPush(parts, ext);
    }

    return arrToStr(parts, '.');
};