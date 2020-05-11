<?php


namespace App\Services;

use App\Model\PlaylistManager;
use App\Model\SongManager;

class PlaylistValidator
{
    const BEGINNING_YOUTUBE_ID_AFTER_PARSED_URL = 2;
    const YOUTUBE_ID_LENGTH = 11;

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
            $this->addErrors('playlistName', 'Le titre de la Playlist ne peut pas être vide');
        } else {
            if (!preg_match('/^[-\'a-zA-ZÀ-ÖØ-öø-ÿ_ ]{2,255}+$/', $val)) {
                $this->addErrors(
                    'playlistName',
                    'Le titre doit comprendre entre 2 et 255 caractères avec des chiffres et lettres seulement'
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
        $id        = substr($parsedUrl, self::BEGINNING_YOUTUBE_ID_AFTER_PARSED_URL, self::YOUTUBE_ID_LENGTH);

        //not empty
        if (empty($val)) {
            $this->addErrors('urlSong', 'l\'url de la chanson ne peut pas être vide');
            //verify youtube url  and id length
        } elseif (!preg_match('@^(?:https://(?:www\\.)?youtube.com/)(watch\\?v=)([a-zA-Z0-9]*)@', $val)
            || strlen($id) != self::YOUTUBE_ID_LENGTH) {
            $this->addErrors('pbUrl', 'Merci d\'entrer une URL conforme ex:
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

    /**
     * @param array $errors
     * @param array $playlist
     * @param array $songs
     * @return array
     */
    public function isPlaylistReadyToInsert(array $errors, array $playlist, array $songs)
    {

        if (empty($playlist) && empty($songs)) {
            $this->addErrors('notEmpty', 'playlist et songs doivent être complétés');
        } elseif (!empty($errors['playlistName']) && !empty($errors['url'])) {
            $this->addErrors('notEmpty2', 'le titre de la playlist ou l\'url contiennent des erreurs');
        }
        return $this->errors;
    }

    /**
     * @param array $playlist
     * @param array $songs
     */
    public function insertPlaylistAndSongs(array $playlist, array $songs)
    {
        $songManager = new SongManager();
        $playlistManager = new PlaylistManager();
        $playlist['playlist_id'] = $playlistManager->insertOnePlaylist($playlist);
        $playlistId = $playlist['playlist_id'];

        foreach ($songs as $song) {
            $parsedUrl = parse_url($song['url'], PHP_URL_QUERY);
            $song['url'] = substr(
                $parsedUrl,
                self::BEGINNING_YOUTUBE_ID_AFTER_PARSED_URL,
                self::YOUTUBE_ID_LENGTH
            );
            $songManager->insertSong($song, $playlistId);
        }
    }
    public function checkIfPlaylistExists(int $id)
    {
        $playlistManager     = new PlaylistManager();
        $playlist = $playlistManager->selectOneById($id);
        if (empty($playlist)) {
            $this->addErrors('playlist', 'Cette playlist n\'existe pas' );
        }
        return $this->errors;
    }
}
