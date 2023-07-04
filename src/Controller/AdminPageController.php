<?php

namespace App\Controller;

use App\Entity\Imprimantes;
use App\Form\ImprimanteFormType;
use App\Repository\TypePaperRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ImprimantesRepository;
use App\Repository\PricecopynbRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PricecopycolorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPageController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_page')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('admin_page/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/parametres', name: 'indexParametre')]
    public function parametre(): Response
    {
        
        $user = $this->getUser();
        return $this->render('admin_page/indexParametres.html.twig', [
            'user' => $user,
            
        ]);
    }

    #[Route('/parametres/imprimantes', name: 'indexPrint')]
    public function printIndex(ImprimantesRepository $repository): Response
    {
        $imprimantes = $repository->findAll();
        $user = $this->getUser();
        return $this->render('admin_page/indexPrint.html.twig', [
            'user' => $user,
            'imprimantes' => $imprimantes
            
        ]);
    }

    #[Route('/parametres/imprimantes/add', name: 'addPrint')]
    public function printAdd(EntityManagerInterface $manager, Request $request): Response
    {
        $imprimantes = new Imprimantes;
        $form = $this->createForm(ImprimanteFormType::class, $imprimantes);
        $form->handleRequest($request);
        $user = $this->getUser();
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($imprimantes);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre nouvelle imprimante a bien été créé"
            );
            return $this->redirectToRoute('indexPrint');
        }
        return $this->render('admin_page/formPrint.html.twig', ['form'=>$form->createView(), 'user'=>$user]);
    }

    #[Route('/dashboard/apiCaisse/print/{id}/delete', name: 'deletePrint')]
    public function printDelete(EntityManagerInterface $manager, Imprimantes $prints ): Response
    {
        $this->addFlash('success', "Travail supprimé");
        
         $manager->remove($prints);
         $manager->flush();
         return $this->redirectToRoute('indexPrint');
    }

    #[Route('/dashboard/apiCaisse/print/{id}/edit', name: 'editPrint')]
    public function printEdit(EntityManagerInterface $manager, Imprimantes $prints, Request $request):Response
    {
        $form = $this->createForm(ImprimanteFormType::class, $prints);
        $form->handleRequest($request); 
        $user = $this->getUser(); 
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($prints);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre imprimante a bien été modifiée"
            );
            return $this->redirectToRoute('indexPrint');
        }
        return $this->render('admin_page/formPrint.html.twig', [
            "print" => $prints,
            'form'=>$form->createView(),
            'user'=>$user
            
        ]);

    }





    #[Route('/parametres/copies', name: 'indexCopies')]
    public function copieIndex(PricecopycolorRepository $color, PricecopynbRepository $nb): Response
    {
       
        $pricecolor = $color->findAll();
        $pricenb = $nb->findAll();
        $user = $this->getUser();
        return $this->render('admin_page/indexCopies.html.twig', [
            'user' => $user,
            'colors' => $pricecolor,
            'nbs' => $pricenb
            
            
        ]);
    }

    #[Route('/parametres/papers', name: 'indexPapers')]
    public function paperIndex(TypePaperRepository $paper, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
    $queryBuilder = $paper->createQueryBuilder('p');
    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),
        $request->query->getInt('page', 1),
        8 // Number of results per page
    );
        
        return $this->render('admin_page/indexPapers.html.twig', [
            'user' => $user,
            'papers'=>$pagination
        ]);
    }
}
