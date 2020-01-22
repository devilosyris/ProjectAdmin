<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Avatar;
use App\Form\AvatarType;
use App\Form\AccountType;
use Cocur\Slugify\Slugify;
use App\Service\TopBarService;
use App\Repository\UserRepository;
use App\Repository\AvatarRepository;
use App\Service\FileUploaderService;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/utilisateurs/{page<\d+>?1}", name="users")
     */
    public function index(TopBarService $topbar, PaginationService $pagination) {

        $pagination->setEntityClass(User::class);

        return $this->render('/admin/user/index.html.twig', [
            'controller_name' => 'Utilisateurs',
            'topbar' => $topbar,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route({
     *  "/admin/user/modifier/",
     *  "/admin/user/{slug}/modifier/"
     * }, name="user_edit")
     * 
     * @Route("/admin/utilisateurs/modifier/{userId}", name="user_edit_id")
     */
    public function edit($userId = null, $slug = null, UserRepository $users, Request $request, AvatarRepository $avatar, FileUploaderService $fileUploader, TopBarService $topbar)
    {
        $route = $request->attributes->get('_route');
        if($route == "user_edit_id") {
            $user =$users->find($userId);
        } else {
            if($slug == null){
                $user = $this->getUser();
            }else{
                $user = $users->findOneBySlug($slug);
            }
        }
        
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            if(empty($user->getSlug()) OR ($user->getSlug() == null)){
                $slugify = new Slugify;
                $user->setSlug($slugify->slugify($user->getFirstname(). ' ' . $user->getLastname()));
            }
            $manager->persist($user);
            $manager->flush();

            if($route == "user_edit_id") {
                return $this->redirectToRoute('users');
            } else {
                return $this->redirectToRoute('user_edit', array(
                    'slug' => $user->getSlug(),
                ));
            }
        }

        $lastAvatar = $avatar->findOneByUser($user->getId());
        $userAvatar = new Avatar();
        $formAvatar = $this->createForm(AvatarType::class, $userAvatar);
        $formAvatar->handleRequest($request);
        // Verify the valadity of form
        if($formAvatar->isSubmitted() && $formAvatar->isValid()){
            /** @var UploadedFile $avatarFile */
            $avatarFile = $formAvatar['file']->getData();
            // so the avatar file must be processed only when a file is uploaded
            if ($avatarFile) {
                $avatarFileName = $fileUploader->setTargetDirectory('source/user/'.$user->getSlug().'/img/avatar')->upload($avatarFile);
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                if($lastAvatar){
                    $lastAvatar->setName($avatarFileName);
                }else{
                    $userAvatar->setName($avatarFileName);
                }
            }
            // Persist the good avatar
            if($lastAvatar){
                $manager->persist($lastAvatar);
            }else{
                $userAvatar->setUser($user);
                $manager->persist($userAvatar);
            }
            $manager->flush();
            return $this->redirectToRoute('user_edit', array(
                'slug' => $user->getSlug(),
            ));
        }

        return $this->render('/admin/user/edit.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            'form_avatar' => $formAvatar->createView(),
            'topbar' => $topbar,
        ]);
    }

    /**
    * @Route("/admin/utilisateurs/supprimer/{userId}", name="user_delete")
    **/
    public function deleteUser($userId, UserRepository $users){
        $manager = $this->getDoctrine()->getManager();
        $user = $users->find($userId);
        $this->addFlash('warning', 'L\'utilisateur <b>'.$user->getFullname().'</b> a bien été supprimée.');
        $manager->remove($user);
        $manager->flush();
        

        return $this->redirectToRoute('users');
    }
}

