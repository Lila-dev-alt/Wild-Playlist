<?php


namespace App\Controller;

use App\Model\QuestionManager;

/**
 * Class QuestionController
 *
 */
class QuestionController extends AbstractController
{
    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show()
    {
        $questionManager = new QuestionManager();
        $questions = $questionManager->selectAll();

        return $this->twig->render('Question/all.html.twig', ['questions' => $questions]);
    }

    /**
     * Handle id deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $questionManager = new QuestionManager();
        $questionManager->delete($id);
        header('Location:/question/show');

        exit();
    }

    /**
    * Display item edition page specified by $id
    *
    * @param int $id
    * @return string
     */
    public function edit(int $id): string
    {
        $questionManager = new QuestionManager();
        $question = $questionManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $questionManager->update($_POST);
            // TODO: faut-il rediriger vers show ou laisser sur edit ?
            header('Location:/question/edit/' . $_POST['id']);
        }

        return $this->twig->render('Question/edit.html.twig', ['question' => $question]);
    }

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
