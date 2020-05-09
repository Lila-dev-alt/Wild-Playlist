<?php


namespace App\Controller;

use App\Model\LikesManager;

class LikesController extends AbstractController
{
    /**
     * @return false|string
     */
    public function add()
    {
        $json   = file_get_contents('php://input');
        $object = json_decode($json);
        $likesData= [];
        $likesData['user']     = $object->user;
        $likesData['playlist'] = $object->playlist;
        //Persist data in DB
        $likesManager = new LikesManager();
        $likesManager->insert($likesData);
        return $json;
    }

}