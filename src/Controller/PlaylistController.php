<?php


namespace App\Controller;

use App\Model\PlaylistManager;

class PlaylistController extends AbstractController
{
    public function list($name)
    {
        $playlistManager = new PlaylistManager();
        $playlists = $playlistManager->selectByName($name);
        return $this->twig->render("Playlist/list.html.twig", [  //fichier twigphp
            'playlists' => $playlists,
        ]);
    }
}
