<?php


namespace App\Services;

use App\Model\UserManager;

class UserValidator
{
    private $data;

    private $errors = [];

    private static $fields = ['pseudo', 'email', 'password'];


    public function __construct($post_data)
    {
        $this->data = $post_data;
    }

    public function validateForm()
    {
        foreach (self::$fields as $field) {
            if (!array_key_exists($field, $this->data)) {
                trigger_error("$field is not present in data");
                return;
            }
        }
        $this->validateUserName();
        $this->validateEmail();
        $this->validatePassword();
        $this->checkIfEmailUsed();
        return $this->errors;
    }

    private function validateUserName()
    {
        $val = trim($this->data['pseudo']);
        if (empty($val)) {
            $this->addErrors('pseudo', 'Le pseudo ne peut pas être vide');
        } else {
            if (!preg_match('/^[a-zA-Z0-9]{6,12}$/', $val)) {
                $this->addErrors(
                    'pseudo',
                    'Le pseudo doit être entre 6 et 12 charactères avec des chiffres et lettres seulement'
                );
            }
        }
    }

    private function validateEmail()
    {
        $val = trim($this->data['email']);
        if (empty($val)) {
            $this->addErrors('email', 'Veuillez remplir votre email');
        } else {
            if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                $this->addErrors('email', 'l\'email doit être valide');
            }
        }
    }

    private function validatePassword()
    {
        $val = trim($this->data['password']);
        if (empty($val)) {
            $this->addErrors('password', 'Le mot de passe doit être rempli');
        } else {
            if (!preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $val)) {
                $this->addErrors(
                    'password',
                    'Le mot de passe doit contenir au moins 8 charactère, une lettre minuscule, une majuscule, et un nombre'
                );
            }
        }
    }


    private function addErrors($key, $value)
    {
        $this->errors[$key] = $value;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function checkIfEmailUsed()
    {
        $userManager = new UserManager();
        $userExist = $userManager->selectOneByEmail($this->data['email']);
        if (count($userExist) > 0) {
            $this->addErrors('emaile', 'Attention l\'email est déjà utilisé');
        }
    }
    public function noError()
    {
        $noError = "";
        if (count($this->errors) === 0) {
            $noError = "Merci de vous être inscrit. Veuillez-vous connecter";
        }
        return $noError;
    }


    public function clean($var)
    {
        return ucfirst($var);
    }
}
