<?php


namespace App\Controller;

use App\Model\UserManager;
use App\Services\UserValidator;

class UserController extends AbstractController
{
    public function add()
    {

        $errors = [];
        $user = [];
        $noError = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Prepare user array from POST data
            $user = [
                'pseudo'=> $_POST['pseudo'],
                'email'=> $_POST['email'],
                'password'=> $_POST['password']

            ];

            // Check if user is valid
            $validation = new UserValidator($user);
            $validation->validateForm();
            $errors = $validation->getErrors();
            $noError = $validation->noError();
            // If no error, insert user in DB
            if ($noError != "") {
                $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);
                $userManager = new UserManager;
                $userManager->insert($user);
                header('Location:/user/login/?' . http_build_query(['no_error' =>$noError]) );
                exit;
            }
        }

        return $this->twig->render('User/add.html.twig', [
            'errors' => $errors,
            'user' => $user,

        ]); //page twig
    }


    public function login()
    {
        $paramTemplate = [
            "success" => "",
            "error" => ""
        ];
        if ($_GET) {
            if (array_key_exists("no_error", $_GET)) {
                $paramTemplate["success"] = $_GET['no_error'];
            } else if (array_key_exists("error", $_GET)) {
                $paramTemplate["error"] = $_GET['error'];
            }
        }

        return $this->twig->render('User/add.html.twig', $paramTemplate);

    }

    public function check()
    {
        $paramTemplate = [
            "error" => ""
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userManager = new UserManager();
            $userData = $userManager->selectOneByUsername($_POST['username']);

            if ($userData) {
                if (password_verify($_POST['passwordConnec'], $userData['password']) && $userData['is_admin']!=='1') {

                    $_SESSION['username'] = $userData['name'];
                    $_SESSION['id'] = $userData['id'];
                    $_SESSION['admin'] = $userData['is_admin'];
                    header('Location:/home/index');// OK Redirect
                    exit;
                }elseif(password_verify($_POST['passwordConnec'], $userData['password']) && $userData['is_admin']==='1') {
                    $_SESSION['username'] = $userData['name'];
                    $_SESSION['admin'] = $userData['is_admin'];
                    header('Location:/home/index'); //redirect admin
                    exit;
                } else {
                    $paramTemplate["error"] = "Ce n'est pas le bon mot de passe";
                    header('Location:/user/login/?' . http_build_query($paramTemplate));
                    exit;
                }
            } else {
                $paramTemplate["error"] = "Ce nom d'utilisateur n'existe pas";
                header('Location:/user/login/?' . http_build_query($paramTemplate));
                exit;
            }

        }

    }

    public function logout ()
    {
        $_SESSION = [];
        session_destroy();
        unset($_SESSION);
        header('Location:/home/index/'); //redirection après déconnexion
        exit;
    }



}
