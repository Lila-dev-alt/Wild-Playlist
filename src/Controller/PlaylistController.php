<?php


namespace App\Controller;

use App\Model\PlaylistManager;

class PlaylistController extends AbstractController
{
    public function show()
    {
        $playlistManager = new PlaylistManager();
        $playlists = $playlistManager->selectAll();

        return $this->twig->render("User/show.html.twig", [  //fichier twigphp
            'playlists' => $playlists,
        ]);
    }
}
