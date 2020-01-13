<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Service\TopBarService;
use App\Repository\InvoiceRepository;
use App\Service\FileUploaderService;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceController extends AbstractController
{
    /**
     * @Route("/admin/facture/payés/{page<\d+>?1}", name="paid")
     * @Route("/admin/facture/impayés/{page<\d+>?1}", name="unpaid")
     * @Route("/admin/facture/{page<\d+>?1}", name="invoice")
     */
    public function index(TopBarService $topbar, Request $request, PaginationService $pagination)
    {   
        $pagination->setEntityClass(Invoice::class);
        dump($pagination->setFilter(['paid' => '0']));

        $route = $request->attributes->get('_route');
        if($route == "unpaid") {
            $pagination->setFilter(['paid' => 0]);
        }elseif($route == "paid"){
            $pagination->setFilter(['paid' => 1]);
        }else {
            $pagination->setFilter([]);
        }

        if($pagination->getPage() > $pagination->getTotalPage()) {
            
        }

         return $this->render('/admin/invoice/index.html.twig', [
            'controller_name' => 'Facturation',
            'topbar' => $topbar,
            'pagination' => $pagination
        ]);
    }

    /**
    * @Route("/admin/facture/créer", name="invoice_create")
    * @Route("/admin/facture/modifier/{invoiceId}", name="invoice_edit")
    **/
    public function createOrEdit(TopBarService $topbar, Request $request, InvoiceRepository $repo, $invoiceId = null, FileUploaderService $fileUploader){

        $route = $request->attributes->get('_route');
        $manager = $this->getDoctrine()->getManager();

        if($route == 'invoice_create'){
            $invoice = new Invoice;
        }elseif($route == 'invoice_edit'){
            if($invoiceId > 0) {
               $invoice = $repo->find($invoiceId); 
            }else {
                return $this->redirectToRoute('invoice');
            }
        }else{
            return $this->redirectToRoute('invoice');
        }
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $pdfFile */
            $invoiceFile = $form['pdf']->getData();
            $user = $form['user']->getData();
            // so the Pdf file must be processed only when a file is uploaded
            if ($invoiceFile) {
                if($user){
                    $pdfFileName = $fileUploader->setTargetDirectory('source/user/'.$user->getSlug().'/pdf')->upload($invoiceFile);
                }else{
                    $pdfFileName = $fileUploader->setTargetDirectory('source/pdf/')->upload($invoiceFile);
                }
                // updates the 'InvoiceFilename' property to store the PDF file name
                // instead of its contents
                $invoice->setPdf($pdfFileName);
            }
            $manager->persist($invoice);
            $manager->flush();

            // Redirect to login form
            return $this->redirectToRoute('admin');
        }

        return $this->render('/admin/invoice/edit.html.twig', [
            'controller_name' => 'Facturation',
            'topbar' => $topbar,
            'form' => $form->createView(),
        ]);

    }

    /**
    * 
    **/
    public function edit(){
        
    }

    /**
    * @Route("/admin/facture/supprimer", name="invoice_delete")
    **/
    public function delete(){
        
    }
}
