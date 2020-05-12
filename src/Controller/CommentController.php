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

    public function show()
    {
        $message = [
            'error'=>''
        ];

        if (empty($_SESSION)) {
            header('Location: /home/index/?connected=0');
        } if (!empty($_SESSION)) {
            if (!($_SESSION['admin']=='1')) {
                header('Location:/home/index');
            }
        }

        if ($_GET) {
            if (array_key_exists("error", $_GET[0])) {
                $message["error"] = $_GET[0]['error'];
            }
        }
        $CommentManager = new CommentManager();
        $comments = $CommentManager->selectCommentwithName();
        return $this->twig->render('Comments/comments.html.twig', [
            'comments'=> $comments,
            'message' =>$message

        ]);
    }

    public function delete($id)
    {
        $message = [
            'error'=> ''
        ];
        $CommentManager = new CommentManager();
        $comments = $CommentManager->selectCommentwithName();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['checkbox'])) {
                $message['error'] = 'Merci de sélectionner un commentaire puis appuyer sur le bouton supprimer';
                header('Location:/comment/show/?' . http_build_query([$message]));
                exit;
            } else {
                $comments = [
                'id' => $_POST['checkbox']
                ];

                $commentManager = new CommentManager();
                $commentManager->delete($comments['id']);
                header('Location:/comment/show/');
                exit;
            }
        }
        return $this->twig->render('Comments/comments.html.twig', [
            'comments'=>$comments

        ]);
    }
}
