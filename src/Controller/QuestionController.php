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
        }

        return $this->twig->render('Question/edit.html.twig', ['question' => $question]);
    }
}
