<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AdminController extends AbstractController
{
    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }


    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
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
