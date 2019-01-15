<?php

function varExists($var) {
    return isset($var);
}

function varIsEmpty($var) {
    return empty($var);
}

function varIsNotEmpty($var) {
    return !empty($var);
}

function varIsArr($var) {
    return is_array($var);
}

function varIsNumericArr($var) {
    if (!is_array($var)) return false;
    return array_keys($var) === range(0, count($var) - 1);
}

function varIsNotArr($var) {
    return !is_array($var);
}

function varIsHash($var) {
    return is_object($var) || is_array($var);
}

function varIsNotHash($var) {
    return !is_array($var) && !is_object($var);
}

function varIsNumber($var) {
    return is_numeric($var);
}

function varIsStr($var) {
    return is_string($var);
}