<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('/front/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/portfolio", name="portfolio")
     */
    public function portfolio(ProjectRepository $repo){
        $projects =$repo->findAll();
        return $this->render('/front/portfolio.html.twig', [
            'controller_name' => 'Accueil',
            'projects' => $projects
        ]);
    }
}
