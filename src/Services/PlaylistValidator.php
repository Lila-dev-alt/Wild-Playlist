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
    public function cleanUrl(string $songUrl):string
    {
        $val = trim($songUrl);
        $parsedUrl = parse_url($val, PHP_URL_QUERY);
        $id = substr($parsedUrl, 2, 11);
        return $id;
    }

    public function validateUrlSong($song)
    {
        $val = trim($song);
        $parsedUrl = parse_url($val, PHP_URL_QUERY);
        $id = substr($parsedUrl, 2, 11);

        //not empty
        if (empty($val)) {
            $this->addErrors('urlSong', 'l\'url de la chanson ne peut pas être vide');
            //verify youtube url  and id length
        } elseif (!preg_match('@^(?:https://(?:www\\.)?youtube.com/)(watch\\?v=)([a-zA-Z0-9]*)@', $val)
            || strlen($id) != 11) {
            $this->addErrors('ulrSong2', 'Merci d\'entrer une URL conforme ex:
             \'https://www.youtube.com/watch?v=2Vv-BfVoq4g\'');
        }

        return $this->errors;
    }
}
