<?php


namespace App\Controller;

use App\Model\QuestionManager;

class QuestionController extends AbstractController
{

    public function add()
    {
        $error=[];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['content'])) {
                $error['erreur']= 'la question est vide';
            } else {
                $questionManager = new QuestionManager();
                $question = [
                    'content' => $_POST['content'],
                ];
                $questionManager->insert($question);
                header('Location:/question/add/');
                exit();
            }
        }
        return $this->twig->render('question/add.html.twig', [
            'error' => $error,
        ]);
    }
}
