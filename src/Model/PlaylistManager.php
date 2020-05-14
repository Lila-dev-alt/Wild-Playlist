<?php
namespace App\Model;

class PlaylistManager extends AbstractManager
{
    const TABLE = 'playlist';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param string $name
     * @return array
     */
    public function selectByName(string $name)
    {
        return $this->pdo->query('SELECT p.name, p.id, u.name AS user_name, u.created_at AS date 
                FROM ' . $this-> table . ' p
                JOIN user u ON u.id= p.user_id 
                ORDER BY ' . $name)->fetchAll();
    }

    /**
     * @param array $playlist
     * @return int
     */
    public function insertOnePlaylist(array $playlist)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (name, user_id) VALUES (:name, :user_id)");
        $statement->bindValue('name', $playlist['name'], \PDO::PARAM_STR);
        $statement->bindValue('user_id', $playlist['userId'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function selectOneByUserId(int $userId)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE user_id=:id");
        $statement->bindValue(':id', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
