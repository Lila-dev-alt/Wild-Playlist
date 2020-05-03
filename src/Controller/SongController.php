<?php


namespace App\Controller;

use App\Model\PlaylistManager;
use App\Model\SongManager;
use App\Model\QuestionManager;
use App\Services\PlaylistValidator;

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

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        $errors= [];
        //select Questions
        $questionManager = new QuestionManager();
        $questions = $questionManager->selectAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Prepare user array from POST data
            //récup user_id
            //validator name
            $playlistManager= new PlaylistManager();
            $validator= new PlaylistValidator();
            $playlistName = $validator->cleanInput($_POST['playlistName']);
            $playlist= [
                'name' => $playlistName,
                'user_id' => $_SESSION['id'],
            ];
            $validator->validatePlaylistName($playlist['name']);
            $errors= $validator->getErrors();
            //insert PL (PL name, user_id)
            if (empty($errors)) {
                //commented because of grumphp $playlistId =
                    $playlistManager->insertOnePlaylist($playlist);
            }
              var_dump($_POST);

            //FormValidator (url+namePL)
            //Insert ds song (name, url, pl_id, Q_id)
        }
        return $this->twig->render('Song/add.html.twig', [
            'questions' => $questions,
            'errors' => $errors,
            ]);
    }
}
