<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Invoices;
use App\Form\AbonnesType;
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
    /**
     * Controller action to display information related to the caisse.
     *
     * @return Response
     */
    #[Route('/caisse/info', name: 'app_caisse_info')]
    public function info(): Response
    {
        return $this->render('caisse/info.html.twig', []);
    }

    /**
     * Controller action to handle accounting information related to the caisse.
     *
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/caisse/compta', name: 'app_caisse_compta')]
    public function compta(EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        // Set the start and end date for querying invoices for today
        $startDate = new \DateTime();
        $startDate->setTime(0, 0, 0); 
        $endDate = new \DateTime();
        $endDate->setTime(23, 59, 59); 
        
        // Get the repository for Invoices entity
        $invoicesRepository = $entityManager->getRepository(Invoices::class);

        // Query cash invoices for the user within the specified date range
        $cashInvoices = $invoicesRepository->createQueryBuilder('i')
            ->where('i.date BETWEEN :startDate AND :endDate')
            ->andWhere('i.paymentMethod = :paymentMethod')
            ->andWhere('i.user = :user') 
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('paymentMethod', 'cash')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        // Similar query for bancontact invoices
        $bancontactInvoices = $invoicesRepository->createQueryBuilder('i')
            ->where('i.date BETWEEN :startDate AND :endDate')
            ->andWhere('i.paymentMethod = :paymentMethod')
            ->andWhere('i.user = :user') 
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('paymentMethod', 'bancontact')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        // Calculate the total amount for cash and bancontact invoices
        $totalCash = 0;
        foreach ($cashInvoices as $invoice) {
            $totalCash += $invoice->getTotal();
        } 

        $totalBancontact = 0;
        foreach ($bancontactInvoices as $invoice) {
            $totalBancontact += $invoice->getTotal();
        } 

        // Calculate the overall total
        $total = $totalCash + $totalBancontact;

        return $this->render('caisse/compta.html.twig', [
            'user' => $user,
            'totalCash' => $totalCash,
            'totalBancontact' => $totalBancontact,
            'total' => $total
        ]);
    }

    /**
     * Controller action for handling the caisse index page.
     *
     * @return Response
     */
    #[Route('/caisse/index', name: 'app_caisse_index')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('caisse/index.html.twig', ['user' => $user]);
    }

    /**
     * Controller action for handling the caisse success page.
     *
     * @return Response
     */
    #[Route('/caisse/succes', name: 'app_caisse_succes')]
    public function succes(): Response
    {
        $user = $this->getUser();
        return $this->render('caisse/succes.html.twig', ['user' => $user]);
    }

    /**
     * Controller action for handling the print selection page.
     *
     * @param PrintQueueRepository $typePrint
     * @return Response
     */
    #[Route('/caisse/printselect', name: 'app_caisse_printselect')]
    public function printselect(PrintQueueRepository $typePrint): Response
    {
        $user = $this->getUser();
        $prints = $typePrint->findBy([], ['NumberPrint' => 'ASC']);
        return $this->render('caisse/printSelect.html.twig', ['user' => $user, 'prints' =>$prints]);
    }

    /**
     * Controller action for deleting a print queue item.
     *
     * @param EntityManagerInterface $manager
     * @param PrintQueue $prints
     * @return Response
     */
    #[Route('/dashboard/apiCaisse/print/{id}/delete', name: 'DeletePrint')]
    public function deletePrint(EntityManagerInterface $manager, PrintQueue $prints ): Response
    {
        $this->addFlash('success', "Travail supprimé");
        
         $manager->remove($prints);
         $manager->flush();
         return $this->redirectToRoute('app_caisse_printselect');
    }

    /**
     * Controller action for selecting a print queue item for a specific user.
     *
     * @param [type] $id
     * @param PrintQueueRepository $typePrint
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/caisse/printselectAbo/{id}/select', name: 'printselectAbo')]
    public function printselectAbo($id, PrintQueueRepository $typePrint, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $prints = $typePrint->findBy([], ['NumberPrint' => 'ASC']);
        $userRepository = $entityManager->getRepository(User::class);  
        $selectedUser = $userRepository->find($id);
        if (!$selectedUser) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }    
        return $this->render('caisse/printSelectAbo.html.twig', ['user' => $user, 'prints' => $prints, 'selectedUser' => $selectedUser]);
    }
    
    /**
     * Controller action for displaying information about selected print subscriptions for a user.
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param AbonnementsRepository $typeAbonnement
     * @return Response
     */
    #[Route('/caisse/printAboInfo/info', name: 'printAboInfo')]
    public function printAboInfo(Request $request, EntityManagerInterface $entityManager, AbonnementsRepository $typeAbonnement): Response
    {
        $user = $this->getUser();

        // Get the print IDs from the query parameters
        $printIdsString = $request->query->get('printIds');
        $printIdsArray = explode(',', $printIdsString);

        // Get the user ID from the query parameters
        $userId = $request->query->get('userId');

        // Retrieve the selected user's information from the repository
        $userRepository = $entityManager->getRepository(User::class);
        $selectedAbo = $userRepository->find($userId);

        // Retrieve all available abonnements
        $abonnement = $typeAbonnement->findAll();

        // Retrieve print queues based on the provided print IDs
        $printQueueRepository = $entityManager->getRepository(PrintQueue::class);
        $printQueuesToUpdate = $printQueueRepository->findBy(['id' => $printIdsArray]);

        // Calculate the total color and black/white pages
        $cptcolor = 0;
        $cpnb = 0;
        foreach ($printQueuesToUpdate as $printQueue) {
            $cptcolor += ($printQueue->getEndColor()) - ($printQueue->getStartColor());
            $cpnb += ($printQueue->getEndWhiteBlack()) - ($printQueue->getStartWhiteBlack());
        }

        // Render the printAboInfo template with user, abonnement, and calculated information
        return $this->render('caisse/printAboInfo.html.twig', [
            'user' => $user,
            'abo' => $selectedAbo,
            'abonnements' => $abonnement,
            'cptColor' => $cptcolor,
            'cptnb' => $cpnb,
            'printIds' => $printIdsString
        ]);
    }


    /**
     * Controller action for validating and updating print subscriptions for a user.
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    #[Route('/caisse/valideAboOnly', name: 'valideAboOnly')]
    public function valideAboOnly(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        // Get print IDs, user ID, color and black/white page counts from query parameters
        $printIdsString = $request->query->get('printIds');
        $printIdsArray = explode(',', $printIdsString);
        $userId = $request->query->get('userId');
        $color = $request->query->get('color');
        $nb =  $request->query->get('nb');

        // Retrieve the user entity and update color and black/white counts
        $userEntity = $entityManager->getRepository(User::class)->find($userId);
        $userEntity->setNbrColor($color); 
        $userEntity->setNbrNb($nb);  
        
        // Remove processed print queues
        foreach ($printIdsArray as $queueId) {
            $printQueue = $entityManager->getRepository(PrintQueue::class)->find($queueId);
            if ($printQueue) {
                $entityManager->remove($printQueue);
            }
        }

        $entityManager->flush(); // Make sure to flush changes to the database

        return $this->render('caisse/valideAboOnly.html.twig', [
            'user' => $user,
            'nb' => $nb,
            'color' => $color,
        ]); 
    }

    /**
     * Controller action for handling the noCopie functionality.
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param CatBindingsRepository $catTypeBindings
     * @param BindingsRepository $typebindings
     * @param CatTypePaperRepository $catTypePaper
     * @param TypePaperRepository $typePaper
     * @param CatPhotosRepository $catTypePhotos
     * @param PhotosRepository $typePhotos
     * @param PricecopycolorRepository $typeColors
     * @param PricecopynbRepository $typeNbs
     * @return Response
     */
    #[Route('/caisse/noCopie', name: 'app_caisse_noCopie')]
    public function noCopie( EntityManagerInterface $manager ,Request $request, CatBindingsRepository $catTypeBindings, BindingsRepository $typebindings, CatTypePaperRepository $catTypePaper, TypePaperRepository $typePaper, CatPhotosRepository $catTypePhotos, PhotosRepository $typePhotos, PricecopycolorRepository $typeColors, PricecopynbRepository $typeNbs): Response
    {
        $user = $this->getUser();

        // Retrieve options for different categories and types
        $catBindings = $catTypeBindings->findAll();
        $bindings = $typebindings->findAll();
        $catPapers = $catTypePaper->findAll();
        $papers = $typePaper->findAll();
        $catPhotos = $catTypePhotos ->findAll();
        $photos = $typePhotos ->findAll();
        $colors = $typeColors ->findAll();
        $nbs = $typeNbs -> findAll();

        // Create an instance of Invoices and set default values
        $invoices = new Invoices; 
        $invoices->setClient("Vente au comptoir");

        // Create the form using the InvoiceFormType
        $form = $this->createForm(InvoiceFormType::class, $invoices);
        $form->handleRequest($request);

        // Set the user for the invoice
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

    /**
     * Controller action for handling the Copie functionality.
     *
     *
     * @param EntityManagerInterface $manager 
     * @param Request $request 
     * @param CatBindingsRepository $catTypeBindings 
     * @param BindingsRepository $typebindings 
     * @param CatTypePaperRepository $catTypePaper 
     * @param TypePaperRepository $typePaper 
     * @param CatPhotosRepository $catTypePhotos 
     * @param PhotosRepository $typePhotos 
     * @param PricecopycolorRepository $typeColors 
     * @param PricecopynbRepository $typeNbs 
     * @return Response 
    */
    #[Route('/caisse/Copie', name: 'app_caisse_Copie')]
    public function Copie(
        EntityManagerInterface $manager,
        Request $request,
        CatBindingsRepository $catTypeBindings,
        BindingsRepository $typebindings,
        CatTypePaperRepository $catTypePaper,
        TypePaperRepository $typePaper,
        CatPhotosRepository $catTypePhotos,
        PhotosRepository $typePhotos,
        PricecopycolorRepository $typeColors,
        PricecopynbRepository $typeNbs
    ): Response {
        
        $user = $this->getUser();
        // Get the print IDs from the query parameters
        $printIdsString = $request->query->get('printIds');
        $printIdsArray = explode(',', $printIdsString);

        // Retrieve options for different categories and types
        $catBindings = $catTypeBindings->findAll();
        $bindings = $typebindings->findAll();
        $catPapers = $catTypePaper->findAll();
        $papers = $typePaper->findAll();
        $catPhotos = $catTypePhotos->findAll();
        $photos = $typePhotos->findAll();
        $colors = $typeColors->findAll();
        $nbs = $typeNbs->findAll();

        // Create an instance of Invoices and set default values
        $invoices = new Invoices;
        $invoices->setClient("Vente au comptoir");

        // Create the form using the InvoiceFormType
        $form = $this->createForm(InvoiceFormType::class, $invoices);
        $form->handleRequest($request);

        // Set the user for the invoice
        $invoices->setUser($user);

        // Retrieve print queues based on the provided print IDs
        $printQueueRepository = $manager->getRepository(PrintQueue::class);
        $printQueuesToUpdate = $printQueueRepository->findBy(['id' => $printIdsArray]);

        // Calculate the total color and black/white pages
        $cptcolor = 0;
        $cpnb = 0;
        foreach ($printQueuesToUpdate as $printQueue) {
            $cptcolor += ($printQueue->getEndColor()) - ($printQueue->getStartColor());
            $cpnb += ($printQueue->getEndWhiteBlack()) - ($printQueue->getStartWhiteBlack());
        }

        // Process form submission
        if ($form->isSubmitted() && $form->isValid()) {
            // Remove processed print queues
            foreach ($printQueuesToUpdate as $printQueue) {
                $manager->remove($printQueue);
            }

            // Persist the invoice and flush changes to the database
            $manager->persist($invoices);
            $manager->flush();

            // Add a flash message to indicate success
            $this->addFlash(
                'success',
                "Opération enregistrée avec succès"
            );

            // Redirect to the success page
            return $this->redirectToRoute('app_caisse_succes');
        }

        // Render the copie template with form and relevant options
        return $this->render('caisse/copie.html.twig', [
            'form' => $form->createView(),
            'cptColor' => $cptcolor,
            'cptnb' => $cpnb,
            'user' => $user,
            'catReliures' => $catBindings,
            'reliures' => $bindings,
            'catPapers' => $catPapers,
            'papers' => $papers,
            'catPhotos' => $catPhotos,
            'photos' => $photos,
            'colors' => $colors,
            'nbs' => $nbs,
        ]);
    }

    /**
     * Controller action for handling the aboCopie functionality
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param CatBindingsRepository $catTypeBindings
     * @param BindingsRepository $typebindings
     * @param CatTypePaperRepository $catTypePaper
     * @param TypePaperRepository $typePaper
     * @param CatPhotosRepository $catTypePhotos
     * @param PhotosRepository $typePhotos
     * @param PricecopycolorRepository $typeColors
     * @param PricecopynbRepository $typeNbs
     * @return Response
     */#[Route('/caisse/aboCopie', name: 'app_caisse_AboCopie')]
    public function aboCopie(
        EntityManagerInterface $manager,
        Request $request,
        CatBindingsRepository $catTypeBindings,
        BindingsRepository $typebindings,
        CatTypePaperRepository $catTypePaper,
        TypePaperRepository $typePaper,
        CatPhotosRepository $catTypePhotos,
        PhotosRepository $typePhotos,
        PricecopycolorRepository $typeColors,
        PricecopynbRepository $typeNbs
    ): Response {
        // Retrieve the authenticated user
        $user = $this->getUser();

        // Extract query parameters from the request
        $userId = intval($request->query->get('userId')); 
        $color = $request->query->get('CptColor');
        $nb = $request->query->get('CptNb');
        $resteCouleur = $request->query->get('ResteColor');
        $resteNb = $request->query->get('ResteNb');
        $printIdsString = $request->query->get('printIds');
        $printIdsArray = explode(',', $printIdsString);

        // Retrieve options for different categories and types
        $catBindings = $catTypeBindings->findAll();
        $bindings = $typebindings->findAll();
        $catPapers = $catTypePaper->findAll();
        $papers = $typePaper->findAll();
        $catPhotos = $catTypePhotos->findAll();
        $photos = $typePhotos->findAll();
        $colors = $typeColors->findAll();
        $nbs = $typeNbs->findAll();

        // Retrieve the user entity and related information
        $userEntity = $manager->getRepository(User::class)->find($userId);
        $userName = $userEntity->getName();
        $userSurname = $userEntity->getSurname();

        // Create an instance of Invoices and set client name
        $invoices = new Invoices;
        $invoices->setClient($userName . ' ' . $userSurname);

        // Create the form using the InvoiceFormType
        $form = $this->createForm(InvoiceFormType::class, $invoices);
        $form->handleRequest($request);
        $invoices->setUser($user);

        // Retrieve print queues based on the provided print IDs
        $printQueueRepository = $manager->getRepository(PrintQueue::class);
        $printQueuesToUpdate = $printQueueRepository->findBy(['id' => $printIdsArray]);

        // Process form submission
        if ($form->isSubmitted() && $form->isValid()) {
            // Update user's color and black/white page counts
            $userEntity->setNbrColor($color); 
            $userEntity->setNbrNb($nb);   

            // Remove processed print queues
            foreach ($printQueuesToUpdate as $printQueue) {
                $manager->remove($printQueue);
            }

            // Persist the invoice and flush changes to the database
            $manager->persist($invoices);
            $manager->flush();

            // Add a flash message to indicate success
            $this->addFlash(
                'success',
                "Opération enregistrée avec succès"
            );

            // Redirect to the success page
            return $this->redirectToRoute('app_caisse_succes');
        }

        // Render the aboCopie template with form and relevant options
        return $this->render('caisse/copieAbo.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'catReliures' => $catBindings,
            'reliures' => $bindings,
            'catPapers' => $catPapers,
            'papers' => $papers,
            'catPhotos' => $catPhotos,
            'photos' => $photos,
            'colors' => $colors,
            'nbs' => $nbs,
            'resteCouleur' => $resteCouleur,
            'resteNb' => $resteNb
        ]);
    }

    /**
     * Controller action for handling the 'abo' functionality
     *
     * @param Request $request
     * @param UserRepository $abonneRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/caisse/abo', name: 'app_caisse_abo')]
    public function abo(Request $request, UserRepository $abonneRepository, PaginatorInterface $paginator): Response {
        // Create the search form for subscribers
        $form = $this->createForm(SearchAbonneType::class);
        $form->handleRequest($request);
        $user = $this->getUser();

        // Create a query builder for the abonneRepository
        $queryBuilder = $abonneRepository->createQueryBuilder('a');
        $queryBuilder->orderBy('a.name', 'ASC'); 

        // Apply search filters if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $nom = $data['nom'] ?? ''; //
            $prenom = $data['prenom'] ?? ''; 

            // Add conditions to the query builder for filtering by name and surname
            $queryBuilder
                ->where('a.name LIKE :nom')
                ->andWhere('a.surname LIKE :prenom')
                ->setParameter('nom', '%' . $nom . '%') 
                ->setParameter('prenom', '%' . $prenom . '%');
        }

        // Paginate the results using the paginator
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1), 
            7 
        );

        return $this->render('caisse/abo.html.twig', [
            'form' => $form->createView(), 
            'pagination' => $pagination, 
            'user' => $user 
        ]);
    }


    /**
     * Controller action for handling the 'aboIndex' functionality
     *
     * @return Response
     */
    #[Route('/caisse/abo/index', name: 'app_caisse_abo_index')]
    public function aboIndex(): Response
    {
        $user = $this->getUser();
        return $this->render('users/indexCaisse.html.twig', ['user' => $user]);
            
    }

    
    /**
     * Controller action for deleting an abonné
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param User $user
     * @return Response
     */
    #[Route('/caisse/abo/{id}/delete', name: 'app_abo_delete')]
    public function delete(EntityManagerInterface $manager, Request $request, User $user ): Response
    {
        $this->addFlash('success', "Abonné supprimé");
        
         $manager->remove($user);
         $manager->flush();
         $referer = $request->headers->get('referer');
             return new RedirectResponse($referer);
    }
 

    /**
     * Undocumented functionController action for adding a new abonné
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route('/caisse/abo/add', name: 'app_caisse_abo_add')]
    public function aboAdd(EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher): Response {
        // Create a new User entity
        $user = new User();
        
        // Retrieve the authenticated user
        $users = $this->getUser();
        
        // Create a form using the ClientFormType form type and associate it with the User entity
        $form = $this->createForm(ClientFormType::class, $user);
        $form->handleRequest($request);
        
        // Process the form when submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Generate a random password
            $randomPassword = bin2hex(random_bytes(10));
            
            // Hash and set the user's password
            $user->setPassword($hasher->hashPassword($user, $randomPassword));
            
            // Set the user's roles
            $user->setRoles(['ROLE_USER']);
            
            // Persist the user entity
            $manager->persist($user);
            $manager->flush();

            // Add a flash message to indicate success
            $this->addFlash(
                'success',
                "Votre nouveau abonné a bien été créé"
            );

            // Redirect to the abo index page
            return $this->redirectToRoute('app_caisse_abo_index');
        }

        // Render the 'addCaisse' template with the form and user information
        return $this->render('users/addCaisse.html.twig', ['form' => $form->createView(), 'user' => $users]);
    }

 
    /**
     * Controller action for searching abonnés
     *
     * @param Request $request
     * @param UserRepository $abonneRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/caisse/abo/recherche', name: 'app_caisse_abo_recherche')]
    public function shearAbo(Request $request, UserRepository $abonneRepository, PaginatorInterface $paginator): Response {
        // Create the search form for subscribers
        $form = $this->createForm(SearchAbonneType::class);
        $form->handleRequest($request);

        // Retrieve the authenticated user
        $user = $this->getUser();

        // Create a query builder for the abonneRepository
        $queryBuilder = $abonneRepository->createQueryBuilder('a');
        $queryBuilder->orderBy('a.name', 'ASC'); // Order by name in ascending order

        // Apply search filters if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get data from the form
            $data = $form->getData();
            $nom = $data['nom'] ?? ''; 
            $prenom = $data['prenom'] ?? ''; 

            // Add conditions to the query builder for filtering by name and surname
            $queryBuilder
                ->where('a.name LIKE :nom')
                ->andWhere('a.surname LIKE :prenom')
                ->setParameter('nom', '%' . $nom . '%') 
                ->setParameter('prenom', '%' . $prenom . '%');
        }

        // Paginate the results using the paginator
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1), // Get the current page number from the request query
            7 // Number of results per page
        );

        // Render the 'searchUser' template with form, pagination, and user information
        return $this->render('users/searchUser.html.twig', [
            'form' => $form->createView(), 
            'pagination' => $pagination, 
            'user' => $user 
        ]);
    }

    
    /**
     * Controller action for editing an abonné
     *
     * @param EntityManagerInterface $manager
     * @param User $users
     * @param Request $request
     * @return Response
     */
    #[Route('/caisse/abo/{id}/edit', name: 'editAbo')]
    public function printEdit(EntityManagerInterface $manager, User $users, Request $request): Response {
        // Create a form using the AbonnesType form type and associate it with the provided User entity
        $form = $this->createForm(AbonnesType::class, $users);
        $form->handleRequest($request); // Handle the form submission
        
        // Retrieve the authenticated user
        $user = $this->getUser();
        
        // Process the form when submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the modified user entity
            $manager->persist($users);
            $manager->flush();
            
            // Add a flash message to indicate success
            $this->addFlash(
                'success',
                "Votre abonné a bien été modifié"
            );
            
            // Redirect to the search abonné page
            return $this->redirectToRoute('app_caisse_abo_recherche');
        }
        
        // Render the 'editUser' template with the form, user entity, and authenticated user information
        return $this->render('users/editUser.html.twig', [
            "users" => $users, // Provide the user entity to the template
            'form' => $form->createView(), // Create a view for the form
            'user' => $user // Provide the authenticated user
        ]);
    }



}




