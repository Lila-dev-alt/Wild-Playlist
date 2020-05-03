<?php


namespace App\Services;

class PlaylistValidator
{
    /**
     * @var array
     */
    private $errors = [];


    public function validatePlaylistName($playlistName)
    {
        $val = trim($playlistName);
        if (empty($val)) {
            $this->addErrors('playlistName', 'Le nom de la Playlist ne peut pas être vide');
        } else {
            if (!preg_match('/^[a-zA-Z0-9_ ]{2,255}$/', $val)) {
                $this->addErrors(
                    'playlistName',
                    'Le pseudo doit avoir entre 2 et 255 caractères avec des chiffres et lettres seulement'
                );
            }
        }
        return $this->errors;
    }


    public function cleanInput(string $string)
    {
        return trim(filter_var($string, FILTER_SANITIZE_STRING));
    }

    private function addErrors($key, $value)
    {
        $this->errors[$key] = $value;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
