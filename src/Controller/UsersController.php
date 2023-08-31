<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\WorkersType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersController extends AbstractController
{
    /**
     * Controller action for rendering the list of users in the admin panel
     *
     * @param UserRepository $utilisateurs
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/users', name: 'app_users')]
    public function index(UserRepository $utilisateurs, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
        $queryBuilder = $utilisateurs->createQueryBuilder('p');
        $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),
        $request->query->getInt('page', 1),
        8 
        );
        return $this->render('users/index.html.twig', [
            'user' => $user,
            'paginations'=>$pagination
        ]);
    }


    /**
     * Controller action for adding a new worker in the admin panel
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route('/admin/users/add', name: 'app_worker_add')]
    public function workerAdd(EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $users = $this->getUser();
        $form = $this->createForm(WorkersType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            

            $hash = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Nouveau user créé"
            );

            return $this->redirectToRoute('app_users');
        }
        return $this->render('users/add.html.twig', ['form'=>$form->createView(),
        'user' => $users
    
    ]);
    }

    /**
     * Controller action for deleting a user in the admin panel
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param User $user
     * @return Response
     */
    #[Route('/admin/user/{id}/delete', name: 'app_worker_delete')]
    public function delete(EntityManagerInterface $manager, Request $request, User $user ): Response
    {
        $this->addFlash('success', "User supprimé");
        
         $manager->remove($user);
         $manager->flush();
         $referer = $request->headers->get('referer');
             return new RedirectResponse($referer);
    }

}
