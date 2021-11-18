<?php
session_start();
$controller = filter_input(INPUT_POST, 'controller');
if (empty($controller)) {
    $controller = 'index';
}

switch ($controller) {
    case 'index':
        include('controller/UserController.php');
        break;

    default:
        # code...
        break;
}
