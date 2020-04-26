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
            $user['pseudo'] = $validation->clean($user['pseudo']);
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


        return $this->twig->render('User/add.html.twig', [

            'no_error' => $_GET['no_error'],
        ]);

    }
}
