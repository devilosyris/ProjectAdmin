<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Service\TopBarService;
use App\Service\PaginationService;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    /**
     * @Route("/admin/projet/{page<\d+>?1}", name="project")
     */
    public function index(TopBarService $topbar, PaginationService $pagination)
    {
        $pagination->setEntityClass(Project::class);
        return $this->render('/admin/project/index.html.twig', [
            'controller_name' => 'Liste des Projets',
            'topbar' => $topbar,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/admin/projet/créer", name ="project_create")
     * @Route("/admin/projet/modifier/{projectId}", name ="project_edit")
     */
    public function edit(ProjectRepository $repo, TopBarService $topbar, Request $request, $projectId = null) {

        $route = $request->attributes->get('_route');
        $manager = $this->getDoctrine()->getManager();

        if($route == 'project_create') {
            $project = new Project;
        } elseif($route == 'project_edit') {
            if($projectId > 0) {
                $project = $repo->find($projectId);
            } else {
                return $this->redirectToRoute('project');
            }
        } else {
            return $this->redirectToRoute('project');
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $pdfFile */
            // $projectFile = $form['png']->getData();
            // $project = $form['project']->getData();
            // so the png file must be processed only when a file is uploaded
            // if ($projectFile) {
            //     if($project){
            //         $pngFileName = $fileUploader->setTargetDirectory('source/project/'.$project->getSlug().'/png')->upload($projectFile);
            //     }else{
            //         $pngFileName = $fileUploader->setTargetDirectory('source/png/')->upload($projectFile);
            //     }
            //     // updates the 'projectFilename' property to store the png file name
            //     // instead of its contents
            //     $project->setPdf($pngFileName);
            // }
            $manager->persist($project);
            $manager->flush();
            if($route == 'project_create'){
                $this->addFlash('success', 'Le projet <b>'.$project->getName().'</b> a bien été créée.');
            } else {
                $this->addFlash('success', 'Le projet <b>'.$project->getName().'</b> a bien été modifiée.');
            }
            // Redirect to login form
            return $this->redirectToRoute('project');
        }

        return $this->render('/admin/project/edit.html.twig', [
            'controller_name' => 'Liste des Projets',
            'topbar' => $topbar,
            'form' => $form->createView(),
        ]);
    }

    

    /**
    * @Route("/admin/projet/supprimer/{projectId}", name="project_delete")
    **/
    public function deleteProject($projectId, ProjectRepository $projects){
        $manager = $this->getDoctrine()->getManager();
        $project = $projects->find($projectId);
        $this->addFlash('warning', 'Le projet <b>'.$project->getName().'</b> a bien été supprimée.');
        $manager->remove($project);
        $manager->flush();
        

        return $this->redirectToRoute('project');
    }
}
