<?php

//  Write a PHP program to compute the sum of the two given integer values.
//  If the two values are the same, then returns triple their sum. 
function sum($x, $y)
{
    if ($x != $y) {
        $sum = $x + $y;
    }
    if ($x == $y) {
        $sum = ($x + $y) * 3;
    }
    return $sum;
}

echo sum(3, 2);


echo '<br>';

function check($a, $b)
{

    if ($a == 30 || $b == 30 || $a + $b == 30) {
        return true;
    }

    return false;
}

var_dump(check(20, 25));
