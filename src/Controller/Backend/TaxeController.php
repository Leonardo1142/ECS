<?php

namespace App\Controller\Backend;

use App\Repository\TaxeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/Taxe', name: 'admin.Taxe')]
class TaxeController extends AbstractController
{
    #[Route('/', name: '.index', methods: ['GET'])]
    public function index(TaxeRepository $repo): Response

    {
        $taxes = $repo->findAll();

        return $this->render('Backend/Taxe/index.html.twig', [
            'taxes' => $taxes,
        ]);
    }

}
