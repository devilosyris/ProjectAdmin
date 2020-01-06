<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $user = $this->getUser();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'Tableau de bord',
            'user' => $user,
        ]);
    }
}
