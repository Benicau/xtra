<?php

namespace App\Controller;

use App\Repository\ImprimantesRepository;
use App\Repository\PricecopycolorRepository;
use App\Repository\PricecopynbRepository;
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

    #[Route('/parametres/copies', name: 'indexCopies')]
    public function copieIndex(ImprimantesRepository $repository, PricecopycolorRepository $color, PricecopynbRepository $nb): Response
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
}
