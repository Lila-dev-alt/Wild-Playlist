<?php


namespace App\Controller;

use App\Model\SongManager;

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
}
