'use strict';

exports.strStartsWith = function (str, substr) {
    return typeof str === 'string' && (substr === '' || str.indexOf(substr) === 0);
};

exports.strEndsWith = function (str, substr) {
    return typeof str === 'string' && str.indexOf(substr, str.length - substr.length) !== -1;
};

exports.strToLowerCase = function(str) {
    if (typeof str !== 'string') return str;
    return str.toLowerCase();
};

exports.strGetFormatted = function(str, args) {
    fastredRequire('var');
    
    if (typeof str !== 'string') return str;
    if (!varIsArr(args)) {
        args = [args];
    }
    return str.replace(/%(\d*)/g, function(match, p1) {
        return args[p1 - 1];
    });
};

exports.strGetReplaced = function(str, search, replace) {
     if (typeof str !== 'string') return str;
    return str.split(search).join(replace);
};

exports.strGetTrimmed = function(str, charlist) {
    //  discuss at: http://locutus.io/php/trim/
    // original by: Kevin van Zonneveld (http://kvz.io)
    // improved by: mdsjack (http://www.mdsjack.bo.it)
    // improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
    // improved by: Kevin van Zonneveld (http://kvz.io)
    // improved by: Steven Levithan (http://blog.stevenlevithan.com)
    // improved by: Jack
    //    input by: Erkekjetter
    //    input by: DxGx
    // bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
    //   example 1: trim('    Kevin van Zonneveld    ')
    //   returns 1: 'Kevin van Zonneveld'
    //   example 2: trim('Hello World', 'Hdle')
    //   returns 2: 'o Wor'
    //   example 3: trim(16, 1)
    //   returns 3: '6'

    var whitespace = [' ', '\n', '\r', '\t', '\f', '\x0b', '\xa0', '\u2000', '\u2001', '\u2002', '\u2003', '\u2004', '\u2005', '\u2006', '\u2007', '\u2008', '\u2009', '\u200A', '\u200B', '\u2028', '\u2029', '\u3000'].join('');
    var l = 0;
    var i = 0;
    str += '';

    if (charlist) {
        whitespace = (charlist + '').replace(/([[\]().?/*{}+$^:])/g, '$1');
    }

    l = str.length;
    for (i = 0; i < l; i++) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(i);
            break;
        }
    }

    l = str.length;
    for (i = l - 1; i >= 0; i--) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(0, i + 1);
            break;
        }
    }

    return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
};