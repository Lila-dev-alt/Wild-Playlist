<?php


namespace App\Controller;

use App\Model\SongManager;
use App\Model\QuestionManager;

class SongController extends AbstractController
{
    /**
     * Display item listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */

    public function showOne($userName)
    {
        $songManager= new SongManager();
        $songs= $songManager->showByName($userName);
        return  $this->twig->render('Song/showOne.html.twig', ['songs' => $songs]);
    }
    public function add()
    {
        $questionManager = new QuestionManager();
        $questions = $questionManager->selectAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            var_dump($_POST);
            // Prepare user array from POST data

            //récup user_id

            //insert PL (PL name, user_id)

            //FormValidator (url+namePL)
            //Insert ds song (name, url, pl_id, Q_id)
        }
        return $this->twig->render('Song/add.html.twig', ['questions' => $questions]);
    }
}
