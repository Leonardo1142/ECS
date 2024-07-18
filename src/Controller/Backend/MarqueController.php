<?php

namespace App\Controller\Backend;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/admin/marques', name: 'admin.marques')]
class MarqueController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    )
    {
    }

    #[Route('/', name: '.index')]
    public function index(MarqueRepository $repo): Response
    {
        $marques = $repo->findAll();
        return $this->render('Backend/Marque/index.html.twig', [
            'marques' => $marques,
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $marque = new Marque();

        $form = $this->createForm(marqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($marque);
            $this->em->flush();
            $this->addFlash('success', 'Le marque a bien été créé.');

            return $this->redirectToRoute('admin.marques.index');
        }
        return $this->render('Backend/marque/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(Request $request, Marque $marque): Response|RedirectResponse
    {
        if(!$marque){
        $this->addFlash('success','La marque n\'existe pas');
        return $this->redirectToRoute('admin.marques.index');
    }
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($marque);
            $this->em->flush();

            $this->addFlash('success','La marque a bien été modifiée');
            return $this->redirectToRoute('admin.marques.index');
        }
        return $this->render('Backend/Marque/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(Request $request, ?Marque $marque): Response
    {
        if (!$marque){
            $this->addFlash('error','La marque n\'existe pas');

            return $this->redirectToRoute('admin.marques.index');
        }
        if ($this->isCsrfTokenValid('delete'.$marque->getId(), $request->request->get('token'))) {
            $this->em->remove($marque);
            $this->em->flush();

            $this->addFlash('success','La marque a bien été supprimée');
            return $this->redirectToRoute('admin.marques.index');
        } else {
            $this->addFlash('error','Le jeton CSRF n\'est pas valide');
        }
        return $this->redirectToRoute('admin.marques.index');

    }
}
