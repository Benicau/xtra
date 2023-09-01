<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\Bindings;
use App\Entity\Invoices;
use App\Entity\TypePaper;
use App\Entity\Abonnements;
use App\Entity\Imprimantes;
use App\Entity\Pricecopynb;
use App\Form\PaperFormType;
use App\Form\PhotosFormType;
use App\Form\BindingFormType;
use App\Entity\Pricecopycolor;
use App\Form\AbonnementFormType;
use App\Form\ImprimanteFormType;
use App\Form\PriceCopyNbFormType;
use App\Form\PriceCopyColorFormType;
use App\Repository\PhotosRepository;
use App\Repository\BindingsRepository;
use App\Repository\InvoicesRepository;
use App\Repository\TypePaperRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AbonnementsRepository;
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

    /**
     * Controller action for rendering the admin page
     *
     * @return Response
     */
    #[Route('/admin', name: 'app_admin_page')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('admin_page/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Controller action for rendering the settings page in the admin section.
     *
     * @return Response
     */
        #[Route('/admin/parametres', name: 'indexParametre')]
        public function parametre(): Response
        {
            $user = $this->getUser();
            return $this->render('admin_page/indexParametres.html.twig', [
                'user' => $user,
                
            ]);
        }

    /**
     * Controller action for rendering the financial accounting index page in the admin section.
     *
     * @return Response
     */
    #[Route('/admin/comtpa/index', name: 'indexCompta')]
    public function comptaIndex(InvoicesRepository $invoiceRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        $startDateString = $request->query->get('start_date');
        $endDateString = $request->query->get('end_date');
        $paymentMethod = $request->query->get('payment_method'); // Nouveau paramètre
        

        if ($startDateString && $endDateString) {
            $startDate = (new \DateTime($startDateString))->setTime(0, 0, 0);
            $endDate = (new \DateTime($endDateString))->setTime(23, 59, 59);

            $invoices = $invoiceRepository->findByDateRangeAndPayment($startDate, $endDate, $paymentMethod);
        } else {
            $invoices = $invoiceRepository->findAll();
        }
        $pagination = $paginator->paginate(
            $invoices, 
            $request->query->getInt('page', 1), 
            7 
        );
        $totalAmount = 0;
        foreach ($invoices as $invoice) {
        $totalAmount += $invoice->getTotal();
    }

        return $this->render('admin_page/compta.html.twig', [
            'user' => $user,
            'invoices' => $pagination,
            'startDate' => $startDateString,
            'endDate' => $endDateString,
            'totalAmount' => $totalAmount,
        ]);
    }


    /**
     * Controller action for rendering the printers index page in the settings section of the admin panel.
     *
     * @param ImprimantesRepository $repository
     * @return Response
     */
    #[Route('/admin/parametres/imprimantes', name: 'indexPrint')]
    public function printIndex(ImprimantesRepository $repository): Response
    {
        $imprimantes = $repository->findAll();
        $user = $this->getUser();
        return $this->render('admin_page/indexPrint.html.twig', [
            'user' => $user,
            'imprimantes' => $imprimantes
            
        ]);
    }

    /**
     * Controller action for adding a new printer in the settings section of the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/imprimantes/add', name: 'addPrint')]
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

    /**
     * Controller action for deleting a printer in the settings section of the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Imprimantes $prints
     * @return Response
     */
    #[Route('/admin/parametres/imprimantes/{id}/delete', name: 'deletePrint')]
    public function printDelete(EntityManagerInterface $manager, Imprimantes $prints ): Response
    {
        $this->addFlash('success', "Votre nouvelle imprimante a bien été supprimée");
        
         $manager->remove($prints);
         $manager->flush();
         return $this->redirectToRoute('indexPrint');
    }
   
    /**
     * Controller action for editing a printer in the settings section of the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Imprimantes $prints
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/imprimantes/{id}/edit', name: 'editPrint')]
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

    /**
     * Controller action for rendering the copies pricing index page in the settings section of the admin panel.
     *
     * @param PricecopycolorRepository $color
     * @param PricecopynbRepository $nb
     * @return Response
     */
    #[Route('/admin/parametres/copies', name: 'indexCopies')]
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

    /**
     * Controller action for adding a new color copy price in the copies pricing section of the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/copies/color/add', name: 'pricecoloradd')]
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
 
    /**
     * Controller action for deleting a color copy price in the copies pricing section of the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Pricecopycolor $datas
     * @return Response
     */
    #[Route('/admin/parametres/copies/color/{id}/delete', name: 'pricecolordelete')]
    public function printColorDelete(EntityManagerInterface $manager, Pricecopycolor $datas ): Response
    {
        $this->addFlash('success', "Prix supprimé");
         $manager->remove($datas);
         $manager->flush();
         return $this->redirectToRoute('indexCopies');
    }

    /**
     * Controller action for editing a color copy price in the copies pricing section of the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Pricecopycolor $datas
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/copies/color/{id}/edit', name: 'pricecoloredit')]
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

    /**
     * Controller action for adding a new black and white copy price in the copies pricing section of the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/copies/nb/add', name: 'pricenbadd')]
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

   /**
     * Controller action for deleting a black and white copy price in the copies pricing section of the admin panel
     *
     * @param EntityManagerInterface $manager
     * @param Pricecopynb $datas
     * @return Response
     */
    #[Route('/admin/parametres/copies/nb/{id}/delete', name: 'pricenbdelete')]
    public function printNbDelete(EntityManagerInterface $manager, Pricecopynb $datas ): Response
    {
        $this->addFlash('success', "Prix supprimé");
        
         $manager->remove($datas);
         $manager->flush();
         return $this->redirectToRoute('indexCopies');
    }

    /**
     * Controller action for editing a black and white copy price in the copies pricing section of the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Pricecopynb $datas
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/copies/nb/{id}/edit', name: 'pricenbedit')]
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

    /**
     * Controller action for rendering the subscriptions index page in the settings section of the admin panel.
     *
     * @param AbonnementsRepository $abo
     * @return Response
     */
    #[Route('/admin/parametres/abonnements', name: 'indexAbonnement')]
    public function copieAbo(AbonnementsRepository $abo): Response
    {
        
        $user = $this->getUser();
        $abos = $abo->findAll();
        return $this->render('admin_page/indexAbo.html.twig', [
            'user' => $user,
            "abos" => $abos
        ]);
    }

    /**
     * Controller action for adding a new price of subscription in the subscriptions section of the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/abonnements/add', name: 'addAbonnement')]
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

    /**
     * Controller action for deleting a price of subscription in the subscriptions section of the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Abonnements $datas
     * @return Response
     */
    #[Route('/admin/parametres/abonnements/{id}/delete', name: 'deleteAbonnement')]
    public function deleteAbo(EntityManagerInterface $manager, Abonnements $datas ): Response
    {
        $this->addFlash('success', "Prix supprimé");
        
         $manager->remove($datas);
         $manager->flush();
         return $this->redirectToRoute('indexAbonnement');
    }
     
    /**
     * Controller action for editing a price of subscription in the subscriptions section of the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Abonnements $datas
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/abonnements/{id}/edit', name: 'editAbonnement')]
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
                "Votre abonnement à bien été modifié"
            );
            return $this->redirectToRoute('indexAbonnement');
        }
        return $this->render('admin_page/formAbonnements.html.twig', [
            "data" => $datas,
            'form'=>$form->createView(),
            'user'=>$user
            
        ]);
    }

 
    /**
     * Controller action for deleting an invoice in the financial section of the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Invoices $invoice
     * @return Response
     */
    #[Route('/admin/compta/invoice/delete/{id}', name: 'app_delete_invoice', methods: ['GET'])]
    public function deleteInvoice(EntityManagerInterface $manager, Invoices $invoice): Response
    {
        $this->addFlash('success', "Opération supprimé");
        $manager->remove($invoice);
        $manager->flush();
        
        return $this->redirectToRoute('indexCompta');
    }


    /**
     * Controller action for adding a new paper type price in the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/papers/add', name: 'addpaper')]
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

    
    /**
     * Controller action for deleting a paper type price in the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param TypePaper $paper
     * @return Response
     */
    #[Route('/admin/parametres/paper/{id}/delete', name: 'deletepaper')]
    public function paperDelete(EntityManagerInterface $manager, TypePaper $paper ): Response
    {
        $this->addFlash('success', "Type papier supprimé");
        
         $manager->remove($paper);
         $manager->flush();
         return $this->redirectToRoute('indexPapers');
    }


    /**
     * Controller action for editing a paper type price in the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param TypePaper $data
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/paper/{id}/edit', name: 'editPaper')]
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
                "Votre type de papier a bien été modifié"
            );
            return $this->redirectToRoute('indexPapers');
        }
        return $this->render('admin_page/formPaper.html.twig', [
            "data" => $data,
            'form'=>$form->createView(),
            'user'=>$user
            
        ]);

    }

    /**
     * Controller action for displaying a paginated list of bindings price in the admin panel.
     *
     * @param BindingsRepository $bindings
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/reliures', name: 'indexReliures')]
    public function bindingsIndex(BindingsRepository $bindings, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
        $queryBuilder = $bindings->createQueryBuilder('p');
        $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),
        $request->query->getInt('page', 1),
        8 // Number of results per page
        );
            
         return $this->render('admin_page/indexBindings.html.twig', [
            'user' => $user,
            'paginations'=>$pagination
         ]);
        }
    
    /**
     * Controller action for deleting a binding price in the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Bindings $bindings
     * @return Response
     */
    #[Route('/admin/parametres/reliures/{id}/delete', name: 'deleteReliures')]
    public function bindingsDelete(EntityManagerInterface $manager, Bindings $bindings ): Response
    {
        $this->addFlash('success', "Reliure supprimée");     
        $manager->remove($bindings);
        $manager->flush();
        return $this->redirectToRoute('indexReliures');
    }


    /**
     * Controller action for adding a binding price in the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/reliures/add', name: 'addReliures')]
    public function bindingsAdd(EntityManagerInterface $manager, Request $request): Response
    {
        $data = new Bindings;
        $form = $this->createForm(BindingFormType::class, $data);
        $form->handleRequest($request);
        $user = $this->getUser();
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($data);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre reliures a bien été créée"
            );
            return $this->redirectToRoute('indexReliures');
        }
        return $this->render('admin_page/formBindings.html.twig', ['form'=>$form->createView(), 'user'=>$user]);
    }

    /**
     * Controller action for editing a binding price in the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Bindings $data
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/reliures/{id}/edit', name: 'editReliures')]
    public function bindingsEdit(EntityManagerInterface $manager, Bindings $data, Request $request):Response
    {
        $form = $this->createForm(BindingFormType::class, $data);
        $form->handleRequest($request); 
        $user = $this->getUser(); 
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($data);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre reliure a bien été modifiée"
            );
            return $this->redirectToRoute('indexReliures');
        }
        return $this->render('admin_page/formBindings.html.twig', [
            "data" => $data,
            'form'=>$form->createView(),
            'user'=>$user
            
        ]);

    }
 
    /**
     * ontroller action for displaying a paginated list of photos prices in the admin panel.
     *
     * @param PhotosRepository $photos
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/photos', name: 'indexPhotos')]
    public function photosIndex(PhotosRepository $photos, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
        $queryBuilder = $photos->createQueryBuilder('p');
        $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),
        $request->query->getInt('page', 1),
        8 // Number of results per page
        );
            
         return $this->render('admin_page/indexPhotos.html.twig', [
            'user' => $user,
            'paginations'=>$pagination
         ]);
        }

    /**
     * Controller action for deleting a photo price in the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Photos $photos
     * @return Response
     */
    #[Route('/admin/parametres/photos/{id}/delete', name: 'deletePhotos')]
    public function photosDelete(EntityManagerInterface $manager, Photos $photos ): Response
    {
        $this->addFlash('success', "Prix photo supprimé");     
        $manager->remove($photos);
        $manager->flush();
        return $this->redirectToRoute('indexPhotos');
    }


    /**
     * Controller action for adding a new photo price in the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/photos/add', name: 'addPhotos')]
    public function photosAdd(EntityManagerInterface $manager, Request $request): Response
    {
        $data = new Photos;
        $form = $this->createForm(PhotosFormType::class, $data);
        $form->handleRequest($request);
        $user = $this->getUser();
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($data);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre nouveau prix photo a bien été créé"
            );
            return $this->redirectToRoute('indexPhotos');
        }
        return $this->render('admin_page/formPhotos.html.twig', ['form'=>$form->createView(), 'user'=>$user]);
    }

    /**
     * Controller action for editing a photo price in the admin panel.
     *
     * @param EntityManagerInterface $manager
     * @param Photos $data
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/parametres/photos/{id}/edit', name: 'editPhotos')]
    public function photosEdit(EntityManagerInterface $manager, Photos $data, Request $request):Response
    {
        $form = $this->createForm(PhotosFormType::class, $data);
        $form->handleRequest($request); 
        $user = $this->getUser(); 
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($data);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre prix photo a bien été modifié"
            );
            return $this->redirectToRoute('indexPhotos');
        }
        return $this->render('admin_page/formPhotos.html.twig', [
            "data" => $data,
            'form'=>$form->createView(),
            'user'=>$user
            
        ]);

    }

}




        



