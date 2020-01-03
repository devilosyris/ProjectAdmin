<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('/front/user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * Undocumented function
     *
     * @Route("/inscription", name="user_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder) 
    {
        $manager = $this->getDoctrine()->getManager();

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();

            // Redirect to login form
            return $this->redirectToRoute('admin');
        } 

        return $this->render('/front/user/register.html.twig', [
            'controller_name' => 'Inscription',
            'form' => $form->createView(),
        ]);
    }
}
