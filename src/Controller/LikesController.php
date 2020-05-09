<?php


namespace App\Controller;

use App\Model\LikesManager;
use App\Services\LikesValidator;

class LikesController extends AbstractController
{
    /**
     * @return false|string
     */
    public function add()
    {
        $errors =[];
        $json   = file_get_contents('php://input');
        $object = json_decode($json);
        $likesData= [];
        $likesData['user']     = $object->user;
        $likesData['playlist'] = $object->playlist;
        //$likesValidator = new LikesValidator($likesData);
        //$likesValidator->checkIfUserAddedLike();
        //$errors[]= $likesValidator->getErrors();
        //$likesManager =  new LikesManager();
        //$tests= $likesManager->selectAll();
        //$likes = $likesManager->selectWithUserIdAndPlaylistId(intval($likesData['user']), intval($likesData['playlist']));

            //Persist data in DB
            $likesManager = new LikesManager();
            $likesManager->insert($likesData);

        return $json;
    }
}