<?php


namespace App\Model;


class LikesManager extends AbstractManager
{
    const TABLE = 'user_playlist';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $likesData
     */
    public function insert(array $likesData): void
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (user_id, playlist_id) VALUES (:user, :playlist)");
        $statement->bindValue(':user', $likesData['user'], \PDO::PARAM_INT);
        $statement->bindValue(':playlist', $likesData['playlist'], \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param int $playlistId
     * @return mixed
     */
    public function countLikes(int $playlistId)
    {
        $statement = $this->pdo->prepare("SELECT count(playlist_id) AS nb_likes FROM " . $this->table . " WHERE playlist_id=:id");
        $statement->bindValue('id', $playlistId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

}