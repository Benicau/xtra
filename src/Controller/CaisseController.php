<?php

namespace App\Controller;

use App\Entity\Bindings;
use App\Entity\Invoices;
use App\Entity\PrintQueue;
use App\Entity\Pricecopynb;
use App\Form\InvoiceFormType;
use App\Repository\PhotosRepository;
use App\Repository\BindingsRepository;
use App\Repository\CatPhotosRepository;
use App\Repository\TypePaperRepository;
use App\Repository\PrintQueueRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CatBindingsRepository;
use App\Repository\PricecopynbRepository;
use App\Repository\CatTypePaperRepository;
use App\Repository\PricecopycolorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CaisseController extends AbstractController
{
    #[Route('/caisse/info', name: 'app_caisse_info')]
    public function info(): Response
    {
        return $this->render('caisse/info.html.twig', []);
    }

    #[Route('/caisse/index', name: 'app_caisse_index')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('caisse/index.html.twig', ['user' => $user]);
        
    }

    #[Route('/caisse/succes', name: 'app_caisse_succes')]
    public function succes(): Response
    {
        $user = $this->getUser();
        return $this->render('caisse/succes.html.twig', ['user' => $user]);
        
    }

    #[Route('/caisse/printselect', name: 'app_caisse_printselect')]
    public function printselect(PrintQueueRepository $typePrint): Response
    {
        $user = $this->getUser();
        $prints = $typePrint->findBy([], ['NumberPrint' => 'ASC']);
        return $this->render('caisse/printSelect.html.twig', ['user' => $user, 'prints' =>$prints]);
    }

    #[Route('/dashboard/apiCaisse/print/{id}/delete', name: 'DeletePrint')]
    public function deletePrint(EntityManagerInterface $manager, PrintQueue $prints ): Response
    {
        $this->addFlash('success', "Travail supprimé");
        
         $manager->remove($prints);
         $manager->flush();
         return $this->redirectToRoute('app_caisse_printselect');
    }


    #[Route('/caisse/noCopie', name: 'app_caisse_noCopie')]
    public function noCopie( EntityManagerInterface $manager ,Request $request, CatBindingsRepository $catTypeBindings, BindingsRepository $typebindings, CatTypePaperRepository $catTypePaper, TypePaperRepository $typePaper, CatPhotosRepository $catTypePhotos, PhotosRepository $typePhotos, PricecopycolorRepository $typeColors, PricecopynbRepository $typeNbs): Response
    {
        $user = $this->getUser();
        
        $catBindings = $catTypeBindings->findAll();
        $bindings = $typebindings->findAll();
        $catPapers = $catTypePaper->findAll();
        $papers = $typePaper->findAll();
        $catPhotos = $catTypePhotos ->findAll();
        $photos = $typePhotos ->findAll();
        $colors = $typeColors ->findAll();
        $nbs = $typeNbs -> findAll();
        $invoices = new Invoices; 
        $invoices->setClient(-1);
        $form = $this->createForm(InvoiceFormType::class, $invoices);
        $form->handleRequest($request);
        $invoices->setUser($user);
        if($form->isSubmitted() && $form->isValid())
        {
            

            $manager->persist($invoices);
            $manager->flush();

            $this->addFlash(
                'success',
                "Opération enregistée avec succes"
            );
            return $this->redirectToRoute('app_caisse_succes');
        }


        return $this->render('caisse/noCopie.html.twig', ['form'=>$form->createView(),'user' => $user, 'catReliures' =>$catBindings, 'reliures'=>$bindings, 'catPapers' =>$catPapers, 'papers' => $papers, 'catPhotos'=>$catPhotos, 'photos'=>$photos, 'colors'=>$colors, 'nbs'=>$nbs]);      
    }



#[Route('/caisse/Copie', name: 'app_caisse_Copie')]
public function Copie( EntityManagerInterface $manager ,Request $request, CatBindingsRepository $catTypeBindings, BindingsRepository $typebindings, CatTypePaperRepository $catTypePaper, TypePaperRepository $typePaper, CatPhotosRepository $catTypePhotos, PhotosRepository $typePhotos, PricecopycolorRepository $typeColors, PricecopynbRepository $typeNbs): Response
{
    $user = $this->getUser();
    $printIdsString = $request->query->get('printIds');
    $printIdsArray = explode(',', $printIdsString);
    $catBindings = $catTypeBindings->findAll();
    $bindings = $typebindings->findAll();
    $catPapers = $catTypePaper->findAll();
    $papers = $typePaper->findAll();
    $catPhotos = $catTypePhotos ->findAll();
    $photos = $typePhotos ->findAll();
    $colors = $typeColors ->findAll();
    $nbs = $typeNbs -> findAll();
    $invoices = new Invoices; 
    $invoices->setClient(-1);
    $form = $this->createForm(InvoiceFormType::class, $invoices);
    $form->handleRequest($request);
    $invoices->setUser($user);
    $printQueueRepository = $manager->getRepository(PrintQueue::class);
    $printQueuesToUpdate = $printQueueRepository->findBy(['id' => $printIdsArray]);
    $cptcolor = 0;
    $cpnb = 0;
    foreach ($printQueuesToUpdate as $printQueue) {
        $cptcolor += ($printQueue->getEndColor()) - ($printQueue->getStartColor());
        $cpnb += ($printQueue->getEndWhiteBlack()) - ($printQueue->getStartWhiteBlack());
    }

    if($form->isSubmitted() && $form->isValid())
    {
        foreach ($printQueuesToUpdate as $printQueue) {
            $manager->remove($printQueue);
        }
        $manager->persist($invoices);
        $manager->flush();

        $this->addFlash(
            'success',
            "Opération enregistée avec succes"
        );
        return $this->redirectToRoute('app_caisse_succes');
    }


    return $this->render('caisse/copie.html.twig', ['form'=>$form->createView(),'cptColor' => $cptcolor,'cptnb' => $cpnb, 'user' => $user, 'catReliures' =>$catBindings, 'reliures'=>$bindings, 'catPapers' =>$catPapers, 'papers' => $papers, 'catPhotos'=>$catPhotos, 'photos'=>$photos, 'colors'=>$colors, 'nbs'=>$nbs]);      
}



#[Route('/caisse/abo', name: 'app_caisse_abo')]
    public function abo(): Response
    {
        $user = $this->getUser();
        return $this->render('caisse/abo.html.twig', ['user' => $user]);
        
    }



#[Route('/caisse/abo/index', name: 'app_caisse_abo_index')]
    public function aboIndex(): Response
    {
        $user = $this->getUser();
        return $this->render('users/indexCaisse.html.twig', ['user' => $user]);
        
    }

}