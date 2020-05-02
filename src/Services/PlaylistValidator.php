<?php


namespace App\Services;

class PlaylistValidator
{
    private $errors = [];

    public function validatePlaylistName($playlistName)
    {
        $val = trim(htmlentities($playlistName));
        if (empty($val)) {
            $this->addErrors('playlistName', 'Le nom de la Playlist ne peut pas être vide');
        } else {
            if (!preg_match('/^[a-zA-Z0-9]{2,255}$/', $val)) {
                $this->addErrors(
                    'playlistName',
                    'Le pseudo doit avoir entre 2 et 255 caractères avec des chiffres et lettres seulement'
                );
            }
        }
        return $this->errors;
    }


    private function addErrors($key, $value)
    {
        $this->errors[$key] = $value;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
