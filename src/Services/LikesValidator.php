<?php


namespace App\Services;

use App\Model\LikesManager;

class LikesValidator
{
    private $data;

    private $errors = [];


    public function __construct($postData)
    {
        $this->data = $postData;
    }

    private function addErrors($key, $value)
    {
        $this->errors[$key] = $value;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
    public function checkIfUserAddedLike(int $playlistId)
    {
        $likesManager= new LikesManager();
        $existingLikes = $likesManager->selectWithUserId($_SESSION['id']);
        foreach ($existingLikes as $existingLike) {
            if (in_array($playlistId, $existingLike)) {
                $this->addErrors('like', 'Vous avez déjà ajouté un like à cette playlist');
            }
        }
    }
}
