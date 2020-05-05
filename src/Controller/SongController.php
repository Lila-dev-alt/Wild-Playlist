<?php


namespace App\Controller;


use App\Model\PlaylistManager;
use App\Model\CommentManager;
use App\Model\SongManager;
use App\Model\QuestionManager;
use App\Services\PlaylistValidator;
use http\Env\Request;

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
            'errorComments'=>$errorComments
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //VERIFY IF PL ALREADY EXISTS
            $validator = new PlaylistValidator();
            $checkUserPlaylist = $validator->checkIfUserHasPlaylist($_SESSION['id']);
            if (empty($checkUserPlaylist)) {
                //récup user_id+validator name
                $playlistName = $validator->cleanInput($_POST['name']);
                $playlist = [
                    'name' => $playlistName,
                    'userId' => $_SESSION['id'],
                ];
                $validator->validatePlaylistName($playlist['name']);
                $errors['playlistName'] = $validator->getErrors();
                //FormValidator (url)
                $validation = new PlaylistValidator();
                foreach ($_POST as $input => $value) {
                    if (!is_string($input)) {
                        $songs[$input]['question_id'] = $input;
                        $songs[$input]['url'] = $value;
                        $validation->validateUrlSong($songs[$input]['url']);
                        $errors['url'] = $validation->getErrors();
                    }
                }

                //if no error
                //insert PL (PL name, user_id)
                // Insert ds song (name, url, pl_id, Q_id)
                if (empty($errors['playlistName']) && empty($errors['url'])) {
                    $songManager = new SongManager();
                    $playlistManager = new PlaylistManager();
                    if (!empty($playlist) && !empty($songs)) {
                        $playlist['playlist_id'] = $playlistManager->insertOnePlaylist($playlist);
                        $playlistId = $playlist['playlist_id'];
                      
                        foreach ($songs as $song) {
                            $parsedUrl= parse_url($song['url'], PHP_URL_QUERY);
                            $song['url']= substr($parsedUrl, 2, 11);
                            $songManager->insertSong($song, $playlistId);
                        }
                        header('Location: /song/showone/' . $_SESSION['username'] . '/?added=ok');
                    }
                }
            } else {
                $errors= $validator->getErrors();
            }
        }
        return $this->twig->render('Song/add.html.twig', [
            'questions' => $questions,
            'errors' => $errors,
            'post'=> $_POST,
        ]);
    }
}
