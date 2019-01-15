window.path = function () {
    var pieces = [];

    for (var i = 0, numArgs = arguments.length; i < numArgs; i++) {
        arr = pathToArr(arguments[i]);
        arrMerge(pieces, arr);
    }

    return pathFromArr(pieces);
};

window.pathFromArr = function (arr) {
    return arr.join('/');
};

window.pathToArr = function (path) {
    path += '';
    return path.split(/[\\\/]/).filter(function (n) {
        return n !== ''
    });
};

window.pathGetExt = function(path) {
    var parts = arrFromStr(path, '.');

    if (arrGetCount(parts) > 1) {
        return arrPop(parts);
    }

    return '';
};

window.pathGetWithNewExt = function(path, ext) {
    var parts = arrFromStr(path, '.');

    if (arrGetCount(parts) > 1) {
        arrPop(parts);
    }

    if (varIsNotEmpty(ext)) {
        arrPush(parts, ext);
    }

    return arrToStr(parts, '.');
};