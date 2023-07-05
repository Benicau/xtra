<?php

namespace App\Controller;

use App\Entity\Abonnements;
use App\Entity\TypePaper;
use App\Entity\Imprimantes;
use App\Entity\Pricecopynb;
use App\Form\PaperFormType;
use App\Entity\Pricecopycolor;
use App\Form\AbonnementFormType;
use App\Form\ImprimanteFormType;
use App\Form\PriceCopyColorFormType;
use App\Form\PriceCopyNbFormType;
use App\Repository\AbonnementsRepository;
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

    #[Route('/parametres/imprimantes/{id}/delete', name: 'deletePrint')]
    public function printDelete(EntityManagerInterface $manager, Imprimantes $prints ): Response
    {
        $this->addFlash('success', "Travail supprimé");
        
         $manager->remove($prints);
         $manager->flush();
         return $this->redirectToRoute('indexPrint');
    }

    #[Route('/parametres/imprimantes/{id}/edit', name: 'editPrint')]
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

    #[Route('/parametres/copies/color/add', name: 'pricecoloradd')]
    public function printColorNew(EntityManagerInterface $manager, Request $request ): Response
    {
        $data = new Pricecopycolor();
        $form = $this->createForm(PriceCopyColorFormType::class, $data);
        $form->handleRequest($request);
        $user = $this->getUser();
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($data);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre nouveau prix couleur a bien été créé"
            );
            return $this->redirectToRoute('indexCopies');
        }
        return $this->render('admin_page/formPriceCopy.html.twig', ['form'=>$form->createView(), 'user'=>$user]);
    }

    #[Route('/parametres/copies/color/{id}/delete', name: 'pricecolordelete')]
    public function printColorDelete(EntityManagerInterface $manager, Pricecopycolor $datas ): Response
    {
        $this->addFlash('success', "Prix supprimé");
        
         $manager->remove($datas);
         $manager->flush();
         return $this->redirectToRoute('indexCopies');
    }

    #[Route('/parametres/copies/color/{id}/edit', name: 'pricecoloredit')]
    public function printColorEdit(EntityManagerInterface $manager, Pricecopycolor $datas, Request $request):Response
    {
        $form = $this->createForm(PriceCopyColorFormType::class, $datas);
        $form->handleRequest($request); 
        $user = $this->getUser(); 
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($datas);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre nouveau prix couleur a bien été modifié"
            );
            return $this->redirectToRoute('indexCopies');
        }
        return $this->render('admin_page/formPriceCopy.html.twig', [
            "data" => $datas,
            'form'=>$form->createView(),
            'user'=>$user
            
        ]);

    }




    #[Route('/parametres/copies/nb/add', name: 'pricenbadd')]
    public function printNbNew(EntityManagerInterface $manager, Request $request ): Response
    {
        $data = new Pricecopynb();
        $form = $this->createForm(PriceCopyNbFormType::class, $data);
        $form->handleRequest($request);
        $user = $this->getUser();
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($data);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre nouveau prix N/B a bien été créé"
            );
            return $this->redirectToRoute('indexCopies');
        }
        return $this->render('admin_page/formPricenb.html.twig', ['form'=>$form->createView(), 'user'=>$user]);
    }

    #[Route('/parametres/copies/nb/{id}/delete', name: 'pricenbdelete')]
    public function printNbDelete(EntityManagerInterface $manager, Pricecopynb $datas ): Response
    {
        $this->addFlash('success', "Prix supprimé");
        
         $manager->remove($datas);
         $manager->flush();
         return $this->redirectToRoute('indexCopies');
    }

    #[Route('/parametres/copies/nb/{id}/edit', name: 'pricenbedit')]
    public function printNbEdit(EntityManagerInterface $manager, Pricecopynb $datas, Request $request):Response
    {
        $form = $this->createForm(PriceCopyNbFormType::class, $datas);
        $form->handleRequest($request); 
        $user = $this->getUser(); 
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($datas);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre nouveau prix N/B a bien été modifié"
            );
            return $this->redirectToRoute('indexCopies');
        }
        return $this->render('admin_page/formPricenb.html.twig', [
            "data" => $datas,
            'form'=>$form->createView(),
            'user'=>$user
            
        ]);

    }





    #[Route('/parametres/abonnements', name: 'indexAbonnement')]
    public function copieAbo(AbonnementsRepository $abo): Response
    {
        
        $user = $this->getUser();
        $abos = $abo->findAll();
        return $this->render('admin_page/indexAbo.html.twig', [
            'user' => $user,
            "abos" => $abos
        ]);
    }

    #[Route('/parametres/abonnements/add', name: 'addAbonnement')]
    public function addAbo(EntityManagerInterface $manager, Request $request ): Response
    {
        $data = new Abonnements();
        $form = $this->createForm(AbonnementFormType::class, $data);
        $form->handleRequest($request);
        $user = $this->getUser();
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($data);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre nouvelle abonnement a bien été créé"
            );
            return $this->redirectToRoute('indexAbonnement');
        }
        return $this->render('admin_page/formAbonnements.html.twig', ['form'=>$form->createView(), 'user'=>$user]);
    }


    #[Route('/parametres/abonnements/{id}/delete', name: 'deleteAbonnement')]
    public function deleteAbo(EntityManagerInterface $manager, Abonnements $datas ): Response
    {
        $this->addFlash('success', "Prix supprimé");
        
         $manager->remove($datas);
         $manager->flush();
         return $this->redirectToRoute('indexAbonnement');
    }

    #[Route('/parametres/abonnements/{id}/edit', name: 'editAbonnement')]
    public function editAbo(EntityManagerInterface $manager, Abonnements $datas, Request $request):Response
    {
        $form = $this->createForm(AbonnementFormType::class, $datas);
        $form->handleRequest($request); 
        $user = $this->getUser(); 
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($datas);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre nouvelle abonnement à bien été modifier"
            );
            return $this->redirectToRoute('indexAbonnement');
        }
        return $this->render('admin_page/formAbonnements.html.twig', [
            "data" => $datas,
            'form'=>$form->createView(),
            'user'=>$user
            
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

    #[Route('/parametres/papers/add', name: 'addpaper')]
    public function paperAdd(EntityManagerInterface $manager, Request $request): Response
    {
        $paper = new TypePaper;
        $form = $this->createForm(PaperFormType::class, $paper);
        $form->handleRequest($request);
        $user = $this->getUser();
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($paper);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre type de papier a bien été créé"
            );
            return $this->redirectToRoute('indexPapers');
        }
        return $this->render('admin_page/formPaper.html.twig', ['form'=>$form->createView(), 'user'=>$user]);
    }

    #[Route('/parametres/paper/{id}/delete', name: 'deletepaper')]
    public function paperDelete(EntityManagerInterface $manager, TypePaper $paper ): Response
    {
        $this->addFlash('success', "Type papier supprimé");
        
         $manager->remove($paper);
         $manager->flush();
         return $this->redirectToRoute('indexPapers');
    }


    #[Route('/parametres/paper/{id}/edit', name: 'editPaper')]
    public function paperEdit(EntityManagerInterface $manager, TypePaper $data, Request $request):Response
    {
        $form = $this->createForm(PaperFormType::class, $data);
        $form->handleRequest($request); 
        $user = $this->getUser(); 
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($data);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre type de papier a bien été modifiée"
            );
            return $this->redirectToRoute('indexPapers');
        }
        return $this->render('admin_page/formPaper.html.twig', [
            "data" => $data,
            'form'=>$form->createView(),
            'user'=>$user
            
        ]);

    }


}
