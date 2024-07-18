<?php

namespace App\Controller\Backend;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/genres', name: 'admin.genres')]
class GenreController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    )
    {
    }
    #[Route('/', name: '.index', methods: ['GET'])]
    public function index(GenreRepository $repo): Response
    {
        $genres = $repo->findAll();

        return $this->render('Backend/Genre/index.html.twig', [
            'genres' => $genres,
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $genre = new Genre();

        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($genre);
            $this->em->flush();
            $this->addFlash('success', 'Le genre a bien été créé.');

            return $this->redirectToRoute('admin.genres.index');
        }
        return $this->render('Backend/Genre/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Genre $genre,Request $request ): Response
    {
        if (!$genre){
            $this->addFlash('error', 'Le genre n\'existe pas.');
            return $this->redirectToRoute('admin.genres.index');
        }
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($genre);
            $this->em->flush();

            $this->addFlash('success', 'Le genre a bien été moidifié');

            return $this->redirectToRoute('admin.genres.index');
        }
        return $this->render('Backend/Genre/update.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(Request $request, ?Genre $genre): RedirectResponse
    {
        if (!$genre){
            $this->addFlash('error', 'Le genre n\'existe pas.');

            return $this->redirectToRoute('admin.genres.index');
        }
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('token'))) {
            $this->em->remove($genre);
            $this->em->flush();
            $this->addFlash('error','Le genre à bien été supprimé');
        } else {
            $this->addFlash('error', 'Le jeton CSRF n\'est pas valide');
        }
        return $this->redirectToRoute('admin.genres.index');
    }
}
