<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class AdminController extends AbstractController
{
    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var CsrfTokenManagerInterface */
    private $csrfTokenManager;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        EntityManagerInterface $entityManager
    )
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/delete-movie/{id}/{token}", name="app_admin_delete_movie")
     * @IsGranted("deletion", subject="movie")
     */
    public function deleteMovie(Movie $movie, string $token): Response
    {
        if(!$this->csrfTokenManager->isTokenValid(new CsrfToken('delete_'.$movie->getId(), $token)))
            throw new \RuntimeException('Invalid token');

        $this->entityManager->remove($movie);
        $this->entityManager->flush();

        $this->addFlash('success', 'Movie deleted!');

        return $this->redirectToRoute('app_homepage');
    }


    /**
     * @Route("/admin/reset-database", name="app_admin_reset_database")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function resetDatabase(array $tablesToReset = []): Response
    {
        return new Response('blablabla... reseting db.. blablabla...');
        foreach($tablesToReset as $table) {
            if($table === 'user') {
//                $this->denyAccessUnlessGranted('ROLE_USER_ADMIN');
                if(!$this->authorizationChecker->isGranted('ROLE_USER_ADMIN')) {
                    throw new \Exception();
                }
            }

            $this->dropTable($table);
        }

        return $this->render('admin/index.html.twig');
    }


}
