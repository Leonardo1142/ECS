<?php

namespace App\Controller\Backend;

use App\Entity\Taxe;
use App\Repository\TaxeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'taxe' => $taxe,
        ]);



    }
}
