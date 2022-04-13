<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use App\ThirdPartyApi\OmdbApiGateway;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class MovieController extends AbstractController
{
    /** @var LoggerInterface */
    private $logger;
    /** @var OmdbApiGateway */
    private $omdbApiGateway;

    public function __construct(LoggerInterface $logger, OmdbApiGateway $omdbApiGateway)
    {
        $this->logger = $logger;
        $this->omdbApiGateway = $omdbApiGateway;
    }


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
        dump($_ENV['OMDB_API_KEY']);
        dump($_SERVER['OMDB_API_KEY']);
        dump(env('OMDB_API_KEY')->string());
        $poster = $this->omdbApiGateway->getPosterByMovieTitle($movie->getTitle());
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'poster' => $poster
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

            $this->logger->info('A new movie has been created !');
//            $this->validator->validate($this->getUser());

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
