'use strict';
/**
 * @param value The variable to create JSON string from.
 * @return {string}
 */
exports.json = function(value) {
    return JSON.stringify(value);
};

exports.jsonToValue = function(json) {
    return JSON.parse(json);
};