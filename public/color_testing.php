<?php

function rgbToText($color){

    $array['white'] = array(255, 255, 255);
    $array['silver'] = array(191, 191, 191);
    $array['gray'] = array(127.5, 127.5, 127.5);
    $array['black'] = array(0, 0, 0);
    $array['red'] = array(255, 0, 0);
    $array['maroon'] = array(127.5, 0, 0);
    $array['yellow'] = array(255, 255, 0);
    $array['olive'] = array(127.5, 127.5, 0);
    $array['lime'] = array(0, 255, 0);
    $array['green'] = array(0, 255, 0);
    $array['aqua'] = array(0, 255, 255);
    $array['teal'] = array(0, 127.5, 127.5);
    $array['blue'] = array(0, 0, 255);
    $array['navy'] = array(0, 0, 127.5);
    $array['fuchsia'] = array(255, 0, 255);
    $array['purple'] = array(127.5, 0, 127.5);

    $palette = Array();
    $deviation = PHP_INT_MAX;
    $colorName = '';
    foreach ($array as $title => $current) {
        $curDev = compareColors($current, $color);
        if ($curDev < $deviation) {
            $deviation = $curDev;
            $colorName = $title;
        }
    }
    return $colorName;
}


function compareColors($colorA, $colorB)
{
    return abs($colorA[0] - $colorB[0]) + abs($colorA[1] - $colorB[1]) + abs($colorA[2] - $colorB[2]);
}

function rgbToHex($R, $G, $B)
{

    $R = dechex($R);
    if (strlen($R) < 2)
        $R = '0' . $R;

    $G = dechex($G);
    if (strlen($G) < 2)
        $G = '0' . $G;

    $B = dechex($B);
    if (strlen($B) < 2)
        $B = '0' . $B;

    return $R . $G . $B;
}