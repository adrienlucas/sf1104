<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('homepage/index.html.twig', [
            'latestMovie' => $movieRepository->findLatest(),
        ]);
    }

    /**
     * Deprecated, use direct twig include of the template.
     *
     * @Route("/_fragment/navbar", name="app_navbar")
     */
    public function menu(): Response
    {
        return $this->render('navbar.html.twig');
    }
}
