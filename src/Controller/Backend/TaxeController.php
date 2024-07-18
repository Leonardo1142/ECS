<?php

namespace App\Controller\Backend;

use App\Entity\Taxe;
use App\Form\TaxeType;
use App\Repository\TaxeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/taxes', name: 'admin.taxes')]
class TaxeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/', name: '.index', methods: ['GET'])]
    public function index(TaxeRepository $repo): Response

    {
        $taxes = $repo->findAll();

        return $this->render('Backend/Taxe/index.html.twig', [
            'taxes' => $taxes,
        ]);
    }
#[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $taxe = new Taxe();

        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($taxe);
            $this->em->flush();
            $this->addFlash('success','La taxe a bien été créée');

            return $this->redirectToRoute('admin.taxes.index');
        }

        return $this->render('Backend/Taxe/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(Request $request, Taxe $taxe): Response
    {
        if(!$taxe){
            $this->addFlash('error','La taxe n\'existe pas');
            return $this->redirectToRoute('admin.taxes.index');
        }
        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($taxe);
            $this->em->flush();

            $this->addFlash('success','La taxe a bien été moidifiée');
            return $this->redirectToRoute('admin.taxes.index');
        }
        return $this->render('Backend/Taxe/update.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(Request $request, Taxe $taxe): RedirectResponse
    {
        if(!$taxe){
            $this->addFlash('error','La taxe n\'existe pas');
            return $this->redirectToRoute('admin.taxes.index');
        }
        if($this->isCsrfTokenValid('delete'.$taxe->getId(), $request->request->get('token'))){
            $this->em->remove($taxe);
            $this->em->flush();
            $this->addFlash('success','La taxe a bien été supprimée');
            return $this->redirectToRoute('admin.taxes.index');
        } else{
            $this->addFlash('error','Le jeton CSRF n\'est pas valide');
        }
        return $this->redirectToRoute('admin.taxes.index');
    }

}
