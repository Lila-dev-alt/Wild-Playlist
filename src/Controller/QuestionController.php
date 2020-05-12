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
        if (empty($_SESSION)) {
            header('Location: /home/index/?connected=0');
        }
        if (!empty($_SESSION)) {
            if (!($_SESSION['admin']=='1')) {
                header('Location:/home/index');
            }
        }
        $success = [];
        if (isset($_GET['success'])) {
            $success['success'] = 'Ajoutée avec succès';
        };
        $questionManager = new QuestionManager();
        $questions = $questionManager->selectAll();

        return $this->twig->render('Question/all.html.twig', [
            'questions' => $questions,
            'success' => $success,
        ]);
    }

    /**
     * Handle id deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        if (empty($_SESSION)) {
            header('Location: /home/index/?connected=0');
            exit();
        }
        if (!empty($_SESSION)) {
            if (!($_SESSION['admin']=='1')) {
                header('Location:/home/index');
                exit();
            }
        }
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
            header('Location:/question/edit/' . $_POST['id']);
            exit();
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
                header('Location:/question/show/?success=ok');
                exit();
            }
        }
        return $this->twig->render('Question/add.html.twig', [
            'error' => $error,
            ]);
    }
}
