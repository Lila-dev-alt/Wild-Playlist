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
    public function addName()
    {
        $errors= [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['ajouterPL'])) {
                //récup user_id
                //validator name
                $validator = new PlaylistValidator();
                $playlistManager= new PlaylistManager();
                $playlistName = $validator->cleanInput($_POST['playlistName']);
                $playlist = [
                    'name' => $playlistName,
                    'user_id' => $_SESSION['id'],
                ];
                $validator->validatePlaylistName($playlist['name']);
                $errors = $validator->getErrors();
                //insert PL (PL name, user_id)
                if (empty($errors)) {
                    $playlist['playlist_id'] = $playlistManager->insertOnePlaylist($playlist);
                    header('Location: /song/add');
                }
                $_SESSION['playlistInAdding']= $playlist;
            }
        }
        return $this->twig->render('Song/addPL.html.twig', [
            'errors' => $errors,
        ]);
    }

    public function add()
    {
        $errors=[];
        //select Questions
        $questionManager = new QuestionManager();
        $questions = $questionManager->selectAll();
        //FormValidator (url+namePL)
        //Insert ds song (name, url, pl_id, Q_id)
        $playlist=[];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $input => $value) {
                if (!is_string($input)) {
                    $playlist[$input]['question_id'] = $input;
                    $playlist[$input]['url'] = $value;
                }
            }
            $songManager = new SongManager();
            if (!empty($playlist)) {
                foreach ($playlist as $song) {
                    $playlistId = $_SESSION['playlistInAdding']['playlist_id'];
                    $songManager->insertSong($song, $playlistId);
                }
            }
        }

        return $this->twig->render('Song/add.html.twig', [
            'questions' => $questions,
            'errors' => $errors,
        ]);
    }
}
