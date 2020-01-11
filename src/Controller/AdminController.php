<?php

namespace App\Controller;

use App\Service\TopBarService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(TopBarService $topbar)
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'Tableau de bord',
            'topbar' => $topbar,
        ]);
    }
}
