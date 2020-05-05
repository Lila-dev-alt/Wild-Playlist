<?php
namespace App\Model;

class PlaylistManager extends AbstractManager
{
    const TABLE = 'playlist';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectByName(string $name)
    {
        return $this->pdo->query('SELECT p.name, p.id, u.name AS user_name, u.created_at AS date 
                FROM ' . $this-> table . ' p
                JOIN user u ON u.id= p.user_id 
                ORDER BY ' . $name)->fetchAll();
    }
    public function insertOnePlaylist(array $playlist)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (name, user_id) VALUES (:name, :user_id)");
        $statement->bindValue('name', $playlist['name'], \PDO::PARAM_STR);
        $statement->bindValue('user_id', $playlist['user_id'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }
}
