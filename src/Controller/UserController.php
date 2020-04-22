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
            $user['pseudo'] = $validation->clean($user['pseudo']);

            // If no error, insert user in DB
            if ($noError != "") {
                $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);
                $userManager = new UserManager;
                $userManager->insert($user);
            }
        }
        //  header('Location:/home/index/' ); redirection

        return $this->twig->render('User/add.html.twig', [
            'errors' => $errors,
            'user' => $user,
            'no_error'=> $noError,

        ]); //page twig
    }
}
