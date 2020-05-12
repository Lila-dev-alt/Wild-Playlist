<?php


namespace App\Model;

class CommentManager extends AbstractManager
{
    const TABLE = 'comment';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectComments($playlistId): array
    {
        $statement = $this->pdo->prepare('SELECT c.content, u.name, c.playlist_id, c.user_id FROM ' . $this->table . ' c
            JOIN user u ON u.id= c.user_id
            JOIN playlist p ON  p.id = c.playlist_id 
            WHERE c.playlist_id =:playlistId
            ORDER BY c.id DESC');
        $statement->bindValue(':playlistId', $playlistId, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }
    public function insertComments(array $comment)
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`content`, `user_id`, `playlist_id`) VALUES (:content, :user_id, :playlist_id)");
        $statement->bindValue('content', $comment['comment'], \PDO::PARAM_STR);
        $statement->bindValue('user_id', $comment['id'], \PDO::PARAM_INT);
        $statement->bindValue('playlist_id', $comment['playlist'], \PDO::PARAM_INT);
        return $statement->execute();
    }
    public function selectCommentwithName()
    {
        return $this->pdo->query("SELECT c.id, c.content, u.name AS commentUser, c.user_id FROM " . $this->table . " c
            JOIN user u ON u.id= c.user_id 
            ORDER BY c.id DESC
            ")->fetchAll();
    }
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
