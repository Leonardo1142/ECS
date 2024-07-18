<?php

namespace App\Controller\Backend;

use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/admin/marques','admin.marques')]
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


}
