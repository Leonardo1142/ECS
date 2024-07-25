<?php

namespace App\Controller\Backend;

use App\Entity\Model;
use App\Form\ModelType;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/admin/models', name: 'admin.models')]
class ModelController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    )
    {
    }

    #[Route('/', name: '.index')]
    public function index(ModelRepository $repo): Response
    {
        $models = $repo->findAll();
        return $this->render('Backend/Model/index.html.twig', [
            'models' => $models,
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $model = new Model();

        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($model);
            $this->em->flush();
            $this->addFlash('success', 'Le model a bien été créé.');

            return $this->redirectToRoute('admin.models.index');
        }
        return $this->render('Backend/Model/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(Request $request, Model $model): Response|RedirectResponse
    {
        if(!$model){
            $this->addFlash('error','La model n\'existe pas');
            return $this->redirectToRoute('admin.models.index');
        }
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($model);
            $this->em->flush();

            $this->addFlash('success','La model a bien été modifiée');
            return $this->redirectToRoute('admin.models.index');
        }
        return $this->render('Backend/model/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(Request $request, ?Model $model): Response
    {
        if (!$model){
            $this->addFlash('error','La model n\'existe pas');

            return $this->redirectToRoute('admin.models.index');
        }
        if ($this->isCsrfTokenValid('delete'.$model->getId(), $request->request->get('token'))) {
            $this->em->remove($model);
            $this->em->flush();

            $this->addFlash('success','La model a bien été supprimée');
            return $this->redirectToRoute('admin.models.index');
        } else {
            $this->addFlash('error','Le jeton CSRF n\'est pas valide');
        }
        return $this->redirectToRoute('admin.models.index');

    }
}
