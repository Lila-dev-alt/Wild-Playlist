<?php


namespace App\Controller;

use App\Model\CommentManager;

class CommentController extends AbstractController
{
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Prepare user array from POST data
            $comment = [
                'comment' => $_POST['comment'],
                'id' => $_POST['user'],
                'playlist' => $_POST['playlist']
            ];
            if (strlen($comment['comment']) > 255) {
                $errorComments['tooLong'] = 'Votre commentaire est trop long, il doit faire moins de 255 charactères !';
                header("location: /song/showOne/" . $_POST['username'] . '/?' . http_build_query($errorComments));
                exit;
            }
            if (!empty($comment['id'] && !empty($comment['comment']))) {
                $commentManager = new CommentManager();
                $commentManager->insertComments($comment);
                header("location: /song/showOne/" . $_POST['username'] . "/#comment-form");
                exit;
            } else {
                header("location: /song/showOne/" . $_POST['username']);
                exit;
            }
        }
    }
}
