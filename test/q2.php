<?php
$x = array(
    1, 3, 2, 3, 7, 8, 9, 7, 3
);

$y = array_count_values($x);

echo '<pre>';
print_r($y);

echo '<pre>';
$array = array(
    0 => 'php',
    10 => 'js',
    2 => 'html',
    6 => 'css'
);

echo $key = array_search('css', $array) . "<br />";
echo $key = array_search('js', $array) . "<br />";


echo '<pre>';
$records = array(
    array(
        'id' => 2135,
        'first_name' => 'John',
        'last_name' => 'Doe',
    ),
    array(
        'id' => 3245,
        'first_name' => 'Sally',
        'last_name' => 'Smith',
    ),
    array(
        'id' => 5342,
        'first_name' => 'Jane',
        'last_name' => 'Jones',
    ),
    array(
        'id' => 5623,
        'first_name' => 'Peter',
        'last_name' => 'Doe',
    )
);

$first_names = array_column($records, 'first_name');
print_r($first_names);
