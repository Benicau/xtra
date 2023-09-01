<?php

namespace App\Controller;

use App\Repository\TypePaperRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BugController extends AbstractController
{
    /**
     * Controller action for displaying a paginated list of papers prices in the admin panel.
     *
     * @param TypePaperRepository $paper
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('admin/parametres/papers', name: 'indexPapers')]
    public function paperIndex(TypePaperRepository $paper, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
    $queryBuilder = $paper->createQueryBuilder('p');
    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),
        $request->query->getInt('page', 1),
        8 
    );
        
        return $this->render('admin_page/indexPapers.html.twig', [
            'user' => $user,
            'papers'=>$pagination
        ]);
    }
}
