<?php


namespace App\Model;

class HomeManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'playlist';
    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectPlaylistsWithQuestionAndLimit(int $question, int $limit)
    {
        return $this->pdo->query('
            SELECT u.name user_name, s.url  FROM ' . $this->table . '
            JOIN user u
            ON u.id=playlist.user_id
            JOIN song s 
            ON s.playlist_id = playlist.id
            JOIN question q
            ON q.id = s.question_id
            WHERE q.id =' . $question . '
            ORDER BY u.created_at DESC LIMIT ' . $limit)->fetchAll();
    }
}
