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
    public function add()
    {
        $errors= [];
        $questionManager = new QuestionManager();
        $questions = $questionManager->selectAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //commented because of grumphp $playlistManager= new PlaylistManager();
            // Prepare user array from POST data
            //récup user_id
            $playlist= [
                'name' => $_POST['playlistName'],
                'user_id' => $_SESSION['id'],
            ];

            $validator= new PlaylistValidator();
            $validator->validatePlaylistName($playlist['name']);
            $errors= $validator->getErrors();
            //insert PL (PL name, user_id)
            //validator name

            /** commented beacause of grumphp
              if (empty($errors)){
                $playlistId = $playlistManager->insertOnePlaylist($playlist);
            }
              **/

            //FormValidator (url+namePL)
            //Insert ds song (name, url, pl_id, Q_id)
        }
        return $this->twig->render('Song/add.html.twig', [
            'questions' => $questions,
            'errors' => $errors,
            ]);
    }
}
