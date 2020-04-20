<?php


namespace App\Model;

class PlaylistManager extends AbstractManager
{
    const TABLE = 'playlist';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
