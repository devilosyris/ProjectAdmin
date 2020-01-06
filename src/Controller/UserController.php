<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Form\AvatarType;
use App\Form\AccountType;
use Cocur\Slugify\Slugify;
use App\Repository\UserRepository;
use App\Repository\AvatarRepository;
use App\Service\FileUploaderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route({
     *  "/admin/user/modifier/",
     *  "/admin/user/{slug}/modifier/"
     * }, name="user_edit")
     *
     */
    public function edit($slug = null, UserRepository $users, Request $request, AvatarRepository $avatar, FileUploaderService $fileUploader)
    {
        if($slug == null){
            $user = $this->getUser();
        }else{
            $user = $users->findOneBySlug($slug);
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

            return $this->redirectToRoute('user_edit', array(
                'slug' => $user->getSlug(),
            ));
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
                $avatarFileName = $fileUploader->setTargetDirectory('source/img/user/'.$user->getSlug().'/avatar')->upload($avatarFile);
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
            'user' => $this->getUser(),
        ]);
    }
}

