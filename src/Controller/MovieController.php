<?php

namespace App\Controller;

use Cassandra\Date;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            'movie'=> [
                'title' => 'foobar',
                'description' => str_repeat('foobar ', 20),
                'release_date' => new DateTime('2020-01-01')
            ]
        ]);
    }
}
