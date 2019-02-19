<?php

function floatGetCeil($float) {
    return ceil($float);
}

function floatGetFloor($float) {
    return floor($float);
}

function floatGetRound($float) {
    return round($float);
}

function floatToStr($float, $precision = 2) {
    return number_format($float, $precision, '.', '');
}
