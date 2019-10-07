'use strict';

exports.LOCALE_DEFAULT = 'en-US';

exports.locale = function(value) {
    if (typeof exports._locale === 'undefined') {
        exports._locale = LOCALE_DEFAULT;
    }    

    if (typeof value === 'string') {
        exports._locale = value;
    }

    return exports._locale;
};

exports.localeDatetimeToStr = function(datetime, key = '-dd-mm-yyyy-hh-ii-ss') {
    fastredRequire('arr', 'datetime', 'int', 'var');

    var args = [];
    var obj = datetimeObj(datetime);
    var arr = arrFromStr(key, '-');
    var dayPrecending = false;		
    var value;

    for(i in arr) {
        value = arr[i];
        
        switch (value) {

            case 'd':
            case 'day':
                args.push(parseInt(obj.day));
                dayPrecending = true;
                break;

            case 'dd':
                args.push(intToStr(obj.day, 2));
                dayPrecending = true;
                break;

            case 'h':
            case 'hour':
                args.push(parseInt(obj.hour));
                break;					

            case 'hh':
                args.push(intToStr(obj.hour, 2));
                break;
                
            case 'i':
            case 'minute':
                args.push(parseInt(obj.minute));
                break;
                
            case 'ii':
                args.push(intToStr(obj.minute, 2));
                break;

            case 'm':
                args.push(parseInt(obj.month));
                break;

            case 'mm':
                args.push(intToStr(obj.month, 2));
                break;

            case 'mon':
            case 'month':
                args.push(localeGetStr('-' + value + '-' + obj.month, dayPrecending ? 2 : 1));					
                dayPrecending = false;
                break;

            case 's':
                args.push(parseInt(obj.second));
                break;

            case 'ss':
            case 'second':
                args.push(intToStr(obj.second, 2));
                break;
            
            case 'wd':
            case 'wkd':
            case 'weekday':
                args.push(localeGetStr('-' + value + '-' + datetimeGetWeekday(datetime)));
                break;

            case 'yyyy':
                args.push(intToStr(obj.month, 4));
                break;

            case 'y':
            case 'year':
                args.push(parseInt(obj.year));
                break;
        }
    }

    result = localeGetStr(key, args);
    return varIsEmpty(result) ? arrToStr(args, ' ') : result;
};

exports.localeGetStr = function(key, args, pluralInt) {
    fastredRequire('var');

    var values = localeGetStrObj();
    var str = values[key];

    if (varIsNumber(args)) pluralInt = args;

    if (varIsNumber(pluralInt)) {
        str = localeIntGetPlural(pluralInt, str); 
    }

    if (args !== null) {
        fastredRequire('str');

        str = strGetFormatted(str, args);
    }

    return varIsNotEmpty(str) ? str : key;
};

exports.localeGetStrObj = function() {
    return {};
};

exports.localeGetFirstWeekDay = function() {
    return parseInt(localeGetStr('-weekday-first'));
};

exports.localeFloatToStr = function(float, precision) {
    var decimalPoint = localeGetStr('-decimal-point');
    var thousandsSeparator = localeGetStr('-thousands-separator');

    var parts = float.toFixed(precision).split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);

    return parts.join(decimalPoint);
};

exports.localeIntGetPlural = function(int, forms) {
    fastredRequire('arr', 'str');

    var rules = {
        'en': 0,
        'af': 0,
        'an': 0,
        'anp': 0,
        'as': 0,
        'ast': 0,
        'az': 0,
        'bg': 0,
        'bn': 0,
        'brx': 0,
        'ca': 0,
        'da': 0,
        'de': 0,
        'doi': 0,
        'el': 0,
        'eo': 0,
        'es': 0,
        'es-ar': 0,
        'et': 0,
        'eu': 0,
        'ff': 0,
        'fi': 0,
        'fo': 0,
        'fur': 0,
        'fy': 0,
        'gl': 0,
        'gu': 0,
        'ha': 0,
        'he': 0,
        'hi': 0,
        'hne': 0,
        'hu': 0,
        'hy': 0,
        'ia': 0,
        'it': 0,
        'kk': 0,
        'kl': 0,
        'kn': 0,
        'ku': 0,
        'ky': 0,
        'lb': 0,
        'mai': 0,
        'ml': 0,
        'mn': 0,
        'mni': 0,
        'mr': 0,
        'nah': 0,
        'nap': 0,
        'nb': 0,
        'ne': 0,
        'nl': 0,
        'nn': 0,
        'no': 0,
        'nso': 0,
        'or': 0,
        'pa': 0,
        'pap': 0,
        'pms': 0,
        'ps': 0,
        'pt': 0,
        'rm': 0,
        'rw': 0,
        'sat': 0,
        'sco': 0,
        'sd': 0,
        'se': 0,
        'si': 0,
        'so': 0,
        'son': 0,
        'sq': 0,
        'sv': 0,
        'sw': 0,
        'ta': 0,
        'te': 0,
        'tk': 0,
        'ur': 0,
        'yo': 0,
        'ach': 1,
        'ak': 1,
        'am': 1,
        'arn': 1,
        'br': 1,
        'fa': 1,
        'fil': 1,
        'fr': 1,
        'gun': 1,
        'ln': 1,
        'mfe': 1,
        'mg': 1,
        'mi': 1,
        'oc': 1,
        'pt-br': 1,
        'tg': 1,
        'ti': 1,
        'tr': 1,
        'uz': 1,
        'wa': 1,
        'zh': 2,
        'ay': 2,
        'bo': 2,
        'cgg': 2,
        'dz': 2,
        'id': 2,
        'ja': 2,
        'jbo': 2,
        'ka': 2,
        'km': 2,
        'ko': 2,
        'lo': 2,
        'ms': 2,
        'my': 2,
        'sah': 2,
        'su': 2,
        'th': 2,
        'tt': 2,
        'ug': 2,
        'vi': 2,
        'wo': 2,
        'ru': 3,
        'uk': 3,
        'be': 3,
        'bs': 3,
        'hr': 3,
        'sr': 3,
        'cs': 4,
        'sk': 4,
        'ar': 5,
        'csb': 6,
        'cy': 7,
        'ga': 8,
        'gd': 9,
        'is': 10,
        'jv': 11,
        'kw': 12,
        'lt': 13,
        'lv': 14,
        'me': 15,
        'mk': 16,
        'mnk': 17,
        'mt': 18,
        'pl': 19,
        'ro': 20,
        'sl': 21
    };
    var forms = arrFromStr(forms, '|');
    var n = Math.abs(parseInt(int));

    var language = strToLowerCase(strGetReplaced(locale(), '_', '-'));
    if (language !== 'es-ar' && language !== 'pt-br') {
        language = arrFromStr(language, '-')[0];
    }

    var rule = rules[language] || 0;
    var index = 0;

    if (arrGetCount(forms) > 0) {
        switch (rule) {
            case 0:
                index = (n != 1);
                break;
            case 1:
                index = (n > 1);
                break;
            case 2:
                index = 0;
                break;
            case 3:
                index = (n % 10 == 1 && n % 100 != 11) ? 0 : ((n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20)) ? 1 : 2);
                break;
            case 4:
                index = (n == 1) ? 0 : ((n >= 2 && n <= 4) ? 1 : 2);
                break;
            case 5:
                index = (n == 0) ? 0 : ((n == 1) ? 1 : ((n == 2) ? 2 : ((n % 100 >= 3 && n % 100 <= 10) ? 3 : ((n % 100 >= 11) ? 4 : 5))));
                break;
            case 6:
                index = (n == 1) ? 0 : ((n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20)) ? 1 : 2);
                break;
            case 7:
                index = (n == 1) ? 0 : ((n == 2) ? 1 : ((n != 8 && n != 11) ? 2 : 3));
                break;
            case 8:
                index = (n == 1) ? 0 : ((n == 2) ? 1 : ((n > 2 && n < 7) ? 2 : ((n > 6 && n < 11) ? 3 : 4)));
                break;
            case 9:
                index = (n == 1 || n == 11) ? 0 : ((n == 2 || n == 12) ? 1 : ((n > 2 && n < 20) ? 2 : 3));
                break;
            case 10:
                index = (n % 10 != 1 || n % 100 == 11);
                break;
            case 11:
                index = (n != 0);
                break;
            case 12:
                index = (n == 1) ? 0 : ((n == 2) ? 1 : ((n == 3) ? 2 : 3));
                break;
            case 13:
                index = (n % 10 == 1 && n % 100 != 11) ? 0 : ((n % 10 >= 2 && (n % 100 < 10 || n % 100 >= 20)) ? 1 : 2);
                break;
            case 14:
                index = (n % 10 == 1 && n % 100 != 11) ? 0 : ((n != 0) ? 1 : 2);
                break;
            case 15:
                index = (n % 10 == 1 && n % 100 != 11) ? 0 : ((n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20)) ? 1 : 2);
                break;
            case 16:
                index = (n == 1 || n % 10 == 1) ? 0 : 1;
                break;
            case 17:
                index = (n == 0) ? 0 : ((n == 1) ? 1 : 2);
                break;
            case 18:
                index = (n == 1) ? 0 : ((n == 0 || (n % 100 > 1 && n % 100 < 11)) ? 1 : ((n % 100 > 10 && n % 100 < 20) ? 2 : 3));
                break;
            case 19:
                index = (n == 1) ? 0 : ((n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20)) ? 1 : 2);
                break;
            case 20:
                index = (n == 1) ? 0 : ((n == 0 || (n % 100 > 0 && n % 100 < 20)) ? 1 : 2);
                break;
            case 21:
                index = (n % 100 == 1) ? 0 : ((n % 100 == 2) ? 1 : ((n % 100 == 3 || n % 100 == 4) ? 2 : 3));
                break;
        }

        if (index >= arrGetCount(forms)) {
            index = 0;
        }
    }

    // When index is false, turn it to zero
    index = index || 0;

    return forms[index];    
};
