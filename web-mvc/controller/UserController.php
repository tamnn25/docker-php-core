<?php
include('web-mvc/model/db.php');
include('web-mvc/model/User.php');
class UserContrller
{
    public static function index()
    {
        $action = filter_input(INPUT_POST, 'action');
        if (empty($action)) {
            $action = filter_input(INPUT_GET, 'action');
            if (empty($action)) {
                $action = 'index';
            }
        }
        switch ($action) {
            case 'index':

                if (isset($_SESSION['auth'])) {
                    $user = user::getUser();
                    include('web-mvc/view/ListUser.php');
                    break;
                }

                include('web-mvc/view/welcome.php');
                break;
            case 'store':
                $user_name = filter_input(INPUT_POST, 'user_name');
                $email = filter_input(INPUT_POST, 'email');
                $password = filter_input(INPUT_POST, 'password');
                user::store($user_name, $email, $password);

                $logIn = User::checkLogIn($email, $password);

                if (!empty($logIn)) {
                    $_SESSION['auth'] = [
                        'email' => $email,
                        'password' => $password
                    ];
                    $user = user::getUser();
                    header('Content-Type: application/json; charset=utf-8');

                    echo json_encode(['data' => $user]);

                    die;

                    // include('web-mvc/view/ListUser.php');
                    // break;
                }

                include('web-mvc/view/welcome.php');
                break;
            case 'login':
                include('web-mvc/view/login.php');
                break;
            case 'submit':
                $email = filter_input(INPUT_POST, 'email');
                $password = filter_input(INPUT_POST, 'password');
                $logIn = User::checkLogIn($email, $password);

                if (!empty($logIn)) {
                    $_SESSION['auth'] = [
                        'email' => $email,
                        'password' => $password
                    ];
                    $user = user::getUser();

                    include('web-mvc/view/ListUser.php');
                    break;
                }
                include('web-mvc/view/login.php');
                break;
            case 'register':
                include('web-mvc/view/register.php');
                break;
            case 'logout':
                session_destroy();
                include('web-mvc/view/welcome.php');
                break;
            case 'delete':
                $id = filter_input(INPUT_GET, 'id');
                User::delete($id);
                $user = user::getUser();
                include('web-mvc/view/ListUser.php');
                break;
            case 'edit':
                $id = filter_input(INPUT_GET, 'id');
                $editUser = User::show($id);
                include('web-mvc/view/Edit.php');
                break;
            case 'update':
                $id = filter_input(INPUT_POST, 'id');
                $userName = filter_input(INPUT_POST, 'user_name');
                $email = filter_input(INPUT_POST, 'email');
                $password = filter_input(INPUT_POST, 'password');
                User::update($id, $userName, $email, $password);
                $user = user::getUser();
                include('web-mvc/view/ListUser.php');
                break;
            case 'uploadFile':
                include('web-mvc/view/uploadFile.php');
                break;
            case 'storeFile':
                $file = filter_input(INPUT_POST, 'file');
                var_dump($file);
                die;
                break;
            default:
                # code...
                break;
        }
    }
}
UserContrller::index();
