window.fileGetExt = function(file) {
    return file.split('.').pop();
}

window.fileGetName = function(file) {
    if (!file) return '';
    return file.replace(/^.*[\\\/]/, '');
};

window.fileSizeToStr = function(size, precision) {
    precision = precision || 2;
    size = Math.max(size, 0);

    var units = ['1-byte', '1-kb', '1-mb', '1-gb', '1-tb', '1-pb'];
    var pow = Math.floor((size ? Math.log(size) : 0) / Math.log(1024));
    pow = Math.min(pow, units.length - 1);

    size = size / Math.pow(1024, pow);

    return localeGetStr(units[pow], pow > 0 ? localeFloatToStr(size, precision) : size);
};