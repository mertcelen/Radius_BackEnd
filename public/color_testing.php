<?php

$array['white'] = array(255,255,255);
$array['silver'] = array(191,191,191);
$array['gray'] = array(127.5,127.5,127.5);
$array['black'] = array(0,0,0);
$array['red'] = array(255,0,0);
$array['maroon'] = array(127.5,0,0);
$array['yellow'] = array(255,255,0);
$array['olive'] = array(127.5,127.5,0);
$array['lime'] = array(0,255,0);
$array['green'] = array(0,255,0);
$array['aqua'] = array(0,255,255);
$array['teal'] = array(0,127.5,127.5);
$array['blue'] = array(0,0,255);
$array['navy'] = array(0,0,127.5);
$array['fuchsia'] = array(255,0,255);
$array['purple'] = array(127.5,0,127.5);


$random = array();

for($i = 0 ; $i <= 10; $i++){
    array_push($random,array(rand(0,255),rand(0,255),rand(0,255)));
}

$palette = Array();
echo "<h2>Color Palette</h2>";
echo "<table><tr>";
foreach ($array as $item) {
    array_push($palette,$item);
    $color = rgbToHex($item[0],$item[1],$item[2]);
    echo "<td style='background-color: #$color' width='30px' height='30px'></td>";
}
echo "</tr><tr>";

$counter = 0;
foreach ($array as $item) {
    echo "<td style='text-align: center'>" . $counter++ . "</td>";
}
echo "</tr></table>";

echo "<h2>Random Colors</h2>";
echo "<table><th style='text-align: center'><tr><td>Palette Match</td><td>Random Color</td><td>Color Text</td></tr></th>";
foreach ($random as $name=>$item) {
    $selectedColor = $array[0];
    $deviation = PHP_INT_MAX;
    $colorName = '';
    foreach ($array as $title=>$color){
        $curDev = compareColors($item, $color);
        if ($curDev < $deviation) {
            $deviation = $curDev;
            $selectedColor = $color;
            $colorName = $title;
        }
    }
    $color = rgbToHex($selectedColor[0],$selectedColor[1],$selectedColor[2]);
    echo "<tr><td style='background-color: #$color;color:white' width='120px' height='40px'></td>";
    $color2 = rgbToHex($item[0],$item[1],$item[2]);
    echo "<td style='background-color: #$color2;color:white' width='120px' height='40px'></td>";
    echo "<td style='text-align: center' width='120px' height='40px'>$colorName</td></tr>";
}

echo "</table>";


function compareColors($colorA, $colorB) {
    return abs($colorA[0] - $colorB[0]) + abs($colorA[1] - $colorB[1]) + abs($colorA[2] - $colorB[2]);
}

function rgbToHex($R, $G, $B)
{

    $R = dechex($R);
    if (strlen($R)<2)
        $R = '0'.$R;

    $G = dechex($G);
    if (strlen($G)<2)
        $G = '0'.$G;

    $B = dechex($B);
    if (strlen($B)<2)
        $B = '0'.$B;

    return $R . $G . $B;
}