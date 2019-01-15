<?php

function floatGetRound($float) {
    return round($float);
}

function floatToStr($float, $precision = 2) {
    return number_format($float, $precision, '.', '');
}