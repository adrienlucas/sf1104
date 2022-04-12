<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movies", name="app_list_movies")
     */
    public function listMovies(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/list.html.twig', [
            'movies' => $movieRepository->findBy([]),
//            'movies' => $movieRepository->findByTitle('Dummy 0'),

        ]);
    }

    /**
     * @Route("/movie/{id}", name="app_show_movie", requirements={"id"="\d+"})
     */
    public function showMovie(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/movie/create", name="app_create_movie")
     */
    public function createMovie(Request $request, MovieRepository $movieRepository): Response
    {
        $movieCreationForm = $this->createForm(MovieType::class);
        $movieCreationForm->add('submit', SubmitType::class);

        $movieCreationForm->handleRequest($request);
        if($movieCreationForm->isSubmitted() && $movieCreationForm->isValid()) {

            $this->validator->validate($this->getUser());

            $movie = $movieCreationForm->getData();
            $movieRepository->add($movie);

            $this->addFlash('success', 'Movie created!');
            return $this->redirectToRoute('app_list_movies');
        }

        return $this->render('movie/create.html.twig', [
            'movie_creation_form' => $movieCreationForm->createView(),
        ]);
    }
}
