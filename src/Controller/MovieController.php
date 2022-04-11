<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{id}", name="app_show_movie", requirements={"id"="\d+"})
     */
    public function showMovie(int $id): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie_id' => $id,
        ]);
    }
}
