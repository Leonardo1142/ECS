<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#
class GenreControllerPhpController extends AbstractController
{
    #[Route('/backend/genre/controller/php', name: 'app_backend_genre_controller_php')]
    public function index(): Response
    {
        return $this->render('backend/genre_controller_php/index.html.twig', [
            'controller_name' => 'GenreControllerPhpController',
        ]);
    }
}
