<?php
/**
 * Created by PhpStorm.
 * Playlist: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\HomeManager;

class HomeController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    const PLAYLIST_NB_HOMEPAGE = 3 ;

    public function index() : string
    {
        $homeManager = new HomeManager();
        $playlists=$homeManager->selectPlaylistsWithQuestionAndLimit(1, self::PLAYLIST_NB_HOMEPAGE);
        return $this->twig->render('Home/index.html.twig', [
            'playlists'=>$playlists,
        ]);
    }
}
