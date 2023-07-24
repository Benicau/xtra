<?php

namespace App\Controller;

use App\Entity\Bindings;
use App\Entity\Pricecopynb;
use App\Repository\BindingsRepository;
use App\Repository\CatBindingsRepository;
use App\Repository\CatPhotosRepository;
use App\Repository\CatTypePaperRepository;
use App\Repository\PhotosRepository;
use App\Repository\PricecopycolorRepository;
use App\Repository\PricecopynbRepository;
use App\Repository\TypePaperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/caisse/noCopie', name: 'app_caisse_noCopie')]
    public function noCopie( CatBindingsRepository $catTypeBindings, 
    BindingsRepository $typebindings, 
    CatTypePaperRepository $catTypePaper, 
    TypePaperRepository $typePaper, 
    CatPhotosRepository $catTypePhotos, 
    PhotosRepository $typePhotos,
    PricecopycolorRepository $typeColors,
    PricecopynbRepository $typeNbs
     ): Response
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
        return $this->render('caisse/noCopie.html.twig', ['user' => $user, 'catReliures' =>$catBindings, 'reliures'=>$bindings, 'catPapers' =>$catPapers, 'papers' => $papers, 'catPhotos'=>$catPhotos, 'photos'=>$photos, 'colors'=>$colors, 'nbs'=>$nbs]);      
    }



}


