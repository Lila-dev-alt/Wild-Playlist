<?php


namespace App\Services;

use App\Model\PlaylistManager;

class PlaylistValidator
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param string $playlistName
     * @return mixed
     */
    public function validatePlaylistName(string $playlistName)
    {
        $val = trim($playlistName);
        if (empty($val)) {
            $this->addErrors('playlistName', 'Le nom de la Playlist ne peut pas être vide');
        } else {
            if (!preg_match('/^[a-zA-Z0-9_ ]{2,255}$/', $val)) {
                $this->addErrors(
                    'playlistName',
                    'Le titre doit avoir entre 2 et 255 caractères avec des chiffres et lettres seulement'
                );
            }
        }
        return $this->errors;
    }

    /**
     * @param string $string
     * @return string
     */
    public function cleanInput(string $string): string
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
    public function getErrors(): array
    {
        return $this->errors;
    }
    public function cleanUrl(string $songUrl):string
    {
        $val       = trim($songUrl);
        $parsedUrl = parse_url($val, PHP_URL_QUERY);
        $id        = substr($parsedUrl, 2, 11);
        return $id;
    }

    /**
     * @param string $song
     * @return mixed
     */
    public function validateUrlSong(string $song)
    {
        $val       = trim($song);
        $parsedUrl = parse_url($val, PHP_URL_QUERY);
        $id        = substr($parsedUrl, 2, 11);

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

    /**
     * @param int $userId
     * @return mixed
     */
    public function checkIfUserHasPlaylist(int $userId)
    {
        $playlist     = new PlaylistManager();
        $userPlaylist = $playlist->selectOneByUserId($userId);
        if (!empty($userPlaylist)) {
            $this->addErrors('playlist', 'Vous avez déjà créé une playlist avec ce compte.
            Si vous voulez en créer une nouvelle, créez un aute compte.');
        }
        return $this->errors;
    }
}
