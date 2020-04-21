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
}
