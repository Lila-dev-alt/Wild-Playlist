<?php


namespace App\Controller;

use App\Model\PlaylistManager;
use App\Model\SongManager;
use App\Services\PlaylistValidator;

class PlaylistController extends AbstractController
{
    /**
     * @param string $name
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function list(string $name)
    {
        if (empty($_SESSION)) {
            header('Location: /home/index/?connected=0');
            exit();
        }
        $playlistManager = new PlaylistManager();
        $playlists = $playlistManager->selectByName($name);
        return $this->twig->render("Playlist/list.html.twig", [
            'playlists' => $playlists,
        ]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function delete($name)
    {
        $errors = [];
        $message= [];
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
        $playlistManager = new PlaylistManager();
        $playlists =  $playlistManager->selectByName($name);
        if (isset($_GET['deleted'])) {
            $message['deleted']='Playlist supprimée';
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           //checkifPLExists
            $validator = new PlaylistValidator();
            $validator->checkIfPlaylistExists($_POST['playlistId']);
            $errors = $validator->getErrors();
            if (empty($errors)) {
                $songManager = new SongManager();
                $songManager->delete($_POST['playlistId']);
                $playlistManager->delete($_POST['playlistId']);
                header('Location: /Playlist/delete/date/?deleted=1');
                exit();
            }
        }
        return $this->twig->render('Playlist/delete.html.twig', [
            'playlists'=> $playlists,
            'errors' => $errors,
            'message'  => $message,
        ]);
    }
}
