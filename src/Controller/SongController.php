<?php


namespace App\Controller;

use App\Model\LikesManager;
use App\Model\PlaylistManager;
use App\Model\CommentManager;
use App\Model\SongManager;
use App\Model\QuestionManager;
use App\Services\LikesValidator;
use App\Services\PlaylistValidator;

class SongController extends AbstractController
{

    const BEGINNING_YOUTUBE_ID_AFTER_PARSED_URL = 2;
    const YOUTUBE_ID_LENGTH = 11;

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
        if (empty($_SESSION)) {
            header('Location: /home/index/?connected=0');
        }

        $message=[];
        $errorComments = [
            'tooLong' => ''
        ];
        $songManager= new SongManager();
        $commentManager = new CommentManager();
        $songs= $songManager->showByName($userName);
        if (isset($_GET['added'])) {
            $message['added']='Playlist ajoutée avec succès';
        }
        //affiche les likes
        $likesManager= new LikesManager();
        $likes = $likesManager->countLikes($songs[0]['playlistId']);
        //cherche si déja liké
        $likesValidator= new LikesValidator($songs);
        $likesValidator->checkIfUserAddedLike($songs[0]['playlistId']);
        $message= $likesValidator->getErrors();

        $comments = $commentManager->selectComments($songs[0]['playlistId']);
        if ($_GET) {
            if (array_key_exists("tooLong", $_GET)) {
                $errorComments["tooLong"] = $_GET['tooLong'];
            }
        }
        return $this->twig->render('Song/showOne.html.twig', ['songs' => $songs,
            'comments' => $comments,
            'message'=>$message,
            'username' => $userName,
            'errorComments'=>$errorComments,
            'likes'=> $likes,
        ]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */

    public function add()
    {
        //select Questions
        $questionManager = new QuestionManager();
        $questions = $questionManager->selectAll();
        //Begin add
        $errors=[];
        $songs=[];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //VERIFY IF PL ALREADY EXISTS
            $validator = new PlaylistValidator();
            $checkUserPlaylist = $validator->checkIfUserHasPlaylist($_SESSION['id']);
            if (empty($checkUserPlaylist)) {
                //Getting user_id and validate playlist name
                $playlistName = $validator->cleanInput($_POST['name']);
                $playlist = [
                    'name' => $playlistName,
                    'userId' => $_SESSION['id'],
                ];
                $validator->validatePlaylistName($playlist['name']);
                $errors['playlistName'] = $validator->getErrors();
                //FormValidator (url)
                //$validation = new PlaylistValidator();
                foreach ($_POST as $input => $value) {
                    if (!is_string($input)) {
                        $songs[$input]['question_id'] = $input;
                        $songs[$input]['url'] = $value;
                        $validator->validateUrlSong($songs[$input]['url']);
                        $errors['url'] = $validator->getErrors();
                    }
                }
                //if no error
                if (!empty($songs)) {
                    $validator->isPlaylistReadyToInsert($errors, $playlist, $songs);
                    $errors['isPlaylistReady'] = $validator->getErrors();
                    if (empty($errors['isPlaylistReady'])) {
                        //insert PL (PL name, user_id & Insert ds song (name, url, pl_id, Q_id)
                        $validator->insertPlaylistAndSongs($playlist, $songs);
                        header('Location: /song/showone/' . $_SESSION['username'] . '/?added=1');
                    }
                } else {
                    $errors = $validator->getErrors();
                }
            }
        }
        return $this->twig->render('Song/add.html.twig', [
            'questions' => $questions,
            'errors' => $errors,
            'post'=> $_POST,
        ]);
    }
}
