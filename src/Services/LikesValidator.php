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
    public function checkIfUserAddedLike()
    {
        $likesManager= new LikesManager();
        $likes = $likesManager->selectWithUserIdAndPlaylistId($this->data['user'], $this->data['playlist']);
        if(!empty($likes)){
            $this->addErrors('like', 'Vous avez déjà ajouté un like à cette playlist');
        }
    }

}