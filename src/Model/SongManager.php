<?php


namespace App\Model;

class SongManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'song';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function showByName(string $name)
    {
        $statement = $this->pdo->prepare("SELECT song.id, song.name AS songName, song.url, 
            p.name AS playlistName, u.name, q.content 
            FROM " . self::TABLE .
            " JOIN playlist  p ON p.id = song.playlist_id
            JOIN user u ON u.id = p.user_id
            JOIN question q ON q.id = song.question_id
            WHERE u.name =:name");
        $statement->bindValue(':name', $name, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }
}
