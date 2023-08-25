<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Invoices;
use App\Form\AbonnesType;
use App\Form\WorkersType;
use App\Entity\PrintQueue;
use App\Form\ClientFormType;
use App\Form\InvoiceFormType;
use App\Form\SearchAbonneType;
use App\Repository\AbonnementsRepository;
use App\Repository\UserRepository;
use App\Repository\PhotosRepository;
use App\Repository\BindingsRepository;
use App\Repository\CatPhotosRepository;
use App\Repository\TypePaperRepository;
use App\Repository\PrintQueueRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CatBindingsRepository;
use App\Repository\PricecopynbRepository;
use App\Repository\CatTypePaperRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PricecopycolorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

    #[Route('/caisse/printselectAbo/{id}/select', name: 'printselectAbo')]
    public function printselectAbo($id, PrintQueueRepository $typePrint, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $prints = $typePrint->findBy([], ['NumberPrint' => 'ASC']);
        
        // Utiliser $id pour rechercher des informations sur l'utilisateur
        $userRepository = $entityManager->getRepository(User::class); // Assurez-vous du chemin d'accès correct
        
        $selectedUser = $userRepository->find($id);
        
        if (!$selectedUser) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }
        
        return $this->render('caisse/printSelectAbo.html.twig', ['user' => $user, 'prints' => $prints, 'selectedUser' => $selectedUser]);
    }
    




    #[Route('/caisse/printAboInfo/info', name: 'printAboInfo')]
    public function printAboInfo(Request $request, EntityManagerInterface $entityManager, AbonnementsRepository $typeAbonnement): Response
    {
        $user = $this->getUser();
        $printIdsString = $request->query->get('printIds');
        $printIdsArray = explode(',', $printIdsString);
        $userId = $request->query->get('userId');
        $userRepository = $entityManager->getRepository(User::class);
        $selectedAbo = $userRepository->find($userId);
        $abonnement= $typeAbonnement->findAll();
        $printQueueRepository = $entityManager->getRepository(PrintQueue::class);
        $printQueuesToUpdate = $printQueueRepository->findBy(['id' => $printIdsArray]);
        $cptcolor = 0;
        $cpnb = 0;
        foreach ($printQueuesToUpdate as $printQueue) {
            $cptcolor += ($printQueue->getEndColor()) - ($printQueue->getStartColor());
            $cpnb += ($printQueue->getEndWhiteBlack()) - ($printQueue->getStartWhiteBlack());
        }

       
        return $this->render('caisse/printAboInfo.html.twig', ['user' => $user,'abo' => $selectedAbo,'abonnements' => $abonnement,'cptColor' => $cptcolor,'cptnb' => $cpnb, 'printIds' =>$printIdsString ]);
    }

    #[Route('/caisse/printAboInfo/info', name: 'valideAboOnly')]
    public function valideAboOnly(Request $request)
    {
        $user = $this->getUser();
        $printIdsString = $request->query->get('printIds');
        $printIdsArray = explode(',', $printIdsString);
        $userId = $request->query->get('userId');
        return $this->render('caisse/valideAboOnly.html.twig', []);
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

#[Route('/caisse/aboCopie', name: 'app_caisse_AboCopie')]
public function aboCopie( EntityManagerInterface $manager ,Request $request, CatBindingsRepository $catTypeBindings, BindingsRepository $typebindings, CatTypePaperRepository $catTypePaper, TypePaperRepository $typePaper, CatPhotosRepository $catTypePhotos, PhotosRepository $typePhotos, PricecopycolorRepository $typeColors, PricecopynbRepository $typeNbs): Response
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


    return $this->render('caisse/copieAbo.html.twig', ['form'=>$form->createView(),'cptColor' => $cptcolor,'cptnb' => $cpnb, 'user' => $user, 'catReliures' =>$catBindings, 'reliures'=>$bindings, 'catPapers' =>$catPapers, 'papers' => $papers, 'catPhotos'=>$catPhotos, 'photos'=>$photos, 'colors'=>$colors, 'nbs'=>$nbs]);      
}



#[Route('/caisse/abo', name: 'app_caisse_abo')]
    public function abo(Request $request, UserRepository $abonneRepository, PaginatorInterface $paginator ): Response
    {
        $form = $this->createForm(SearchAbonneType::class);
            $form->handleRequest($request);
            $user = $this->getUser();
            $queryBuilder = $abonneRepository->createQueryBuilder('a');
            $queryBuilder->orderBy('a.name', 'ASC'); // Classement par ordre alphabétique sur le nom
    
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $nom = $data['nom'] ?? '';
                $prenom = $data['prenom'] ?? '';
    
                $queryBuilder
                    ->where('a.name LIKE :nom')
                    ->andWhere('a.surname LIKE :prenom')
                    ->setParameter('nom', '%' . $nom . '%')
                    ->setParameter('prenom', '%' . $prenom . '%');
            }
    
            $pagination = $paginator->paginate(
                $queryBuilder->getQuery(),
                $request->query->getInt('page', 1),
                7 // Nombre de résultats par page
            );
    
            return $this->render('caisse/abo.html.twig', [
                'form' => $form->createView(),
                'pagination' => $pagination,
                'user' => $user
            ]);
        
        
    }



#[Route('/caisse/abo/index', name: 'app_caisse_abo_index')]
    public function aboIndex(): Response
    {
        $user = $this->getUser();
        return $this->render('users/indexCaisse.html.twig', ['user' => $user]);
        
    }

    #[Route('/caisse/abo/{id}/delete', name: 'app_abo_delete')]
    public function delete(EntityManagerInterface $manager, Request $request, User $user ): Response
    {
        $this->addFlash('success', "Abonné supprimé");
        
         $manager->remove($user);
         $manager->flush();
         $referer = $request->headers->get('referer');
             return new RedirectResponse($referer);
    }


#[Route('/caisse/abo/add', name: 'app_caisse_abo_add')]
    public function aboAdd(EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher): Response
    {
    $user = new User();
    $users = $this->getUser();
    $form = $this->createForm(ClientFormType::class, $user);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
        
        $randomPassword = bin2hex(random_bytes(10));
        $user->setPassword($hasher->hashPassword($user, $randomPassword));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "Votre nouveau user à bien eté crée"
        );

        return $this->redirectToRoute('app_caisse_abo_index');
    }
        return $this->render('users/addCaisse.html.twig', ['form'=>$form->createView(),'user' => $users]);
        
    }

    #[Route('/caisse/abo/recherche)', name: 'app_caisse_abo_recherche')]
        public function shearAbo(Request $request, UserRepository $abonneRepository, PaginatorInterface $paginator ):Response
        {
            $form = $this->createForm(SearchAbonneType::class);
            $form->handleRequest($request);
            $user = $this->getUser();
            $queryBuilder = $abonneRepository->createQueryBuilder('a');
            $queryBuilder->orderBy('a.name', 'ASC'); // Classement par ordre alphabétique sur le nom
    
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $nom = $data['nom'] ?? '';
                $prenom = $data['prenom'] ?? '';
    
                $queryBuilder
                    ->where('a.name LIKE :nom')
                    ->andWhere('a.surname LIKE :prenom')
                    ->setParameter('nom', '%' . $nom . '%')
                    ->setParameter('prenom', '%' . $prenom . '%');
            }
    
            $pagination = $paginator->paginate(
                $queryBuilder->getQuery(),
                $request->query->getInt('page', 1),
                7 // Nombre de résultats par page
            );
    
            return $this->render('users/searchUser.html.twig', [
                'form' => $form->createView(),
                'pagination' => $pagination,
                'user' => $user
            ]);


        }

    #[Route('/caisse/abo/{id}/edit', name: 'editAbo')]
        public function printEdit(EntityManagerInterface $manager, User $users, Request $request):Response
        {
            $form = $this->createForm(AbonnesType::class, $users);
            $form->handleRequest($request); 
            $user = $this->getUser(); 
            if($form->isSubmitted() && $form->isValid())
            {
                $manager->persist($users);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "Votre abonné a bien été modifié"
                );
                return $this->redirectToRoute('app_caisse_abo_recherche');
            }
            return $this->render('users/editUser.html.twig', [
                "users" => $users,
                'form'=>$form->createView(),
                'user'=>$user
                
            ]);
    
        }

        



}




