<?php

namespace App\Controller;

use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(AnnoncesRepository $repo): Response
    {
        $annonces = $repo->findAll();
        return $this->render('home/home.html.twig', [
            'annonces' => $annonces
        ]);
    }
}
