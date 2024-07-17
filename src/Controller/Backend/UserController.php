<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/users', name: 'admin.users')]
class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    )
    {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(UserRepository $repo): Response
    {
        return $this->render('Backend/User/index.html.twig', [
            'users' => $repo->findAll(),
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?User $user, Request $request): Response|RedirectResponse
    {
        if (!$user){
            $this->addFlash('error', 'L\'utilisateur n\'existe pas');
            return  $this->redirectToRoute('admin.user.index');
        }
        $form = $this->createForm(UserType::class, $user, ['isAdmin' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Utilisateur mis à jour avec succès');

            return $this->redirectToRoute('admin.users.index');
        }
        return $this->render('Backend/User/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['GET', 'POST'])]
    public function delete(User $user, Request $request): Response
    {
        if(!$user){
            $this->addFlash('error', 'L\'utilisateur n\'existe pas');

            return  $this->redirectToRoute('admin.users.index');
        }
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('token'))) {
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success','L\'utilisateur à bien été supprimé');
        }else{
            $this->addFlash('error', 'Le jeton CSRF n\'est pas valide');
        }
        return $this->redirectToRoute('admin.users.index');
    }




}
