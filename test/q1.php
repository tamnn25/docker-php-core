<?php

$string = 'a b c def';
$array = array('a', 'bcdef');
echo substr($string, 4, -1) . '<br>';

var_dump(in_array('a', $array) . '<br>');
$a = explode(' ', $string);
var_dump($a) . '<br>';

echo '<br>';
$i = 1;
$j = $i--;

echo $i;
echo '<br>';
$a = 10;
while ($a >= 1) {
    echo $a;
    $a--;
}
echo '<br>';

$i = 0;
do {
    echo $i . "<br>";
    $i++;
} while ($i <= 10);

echo '<br>';

$a = 'a b c d e r';

$b = explode(' ', $a);

var_dump(array_key_exists(10, $b));
