<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{
    #[Route('/conference', name: 'HomePage')]
    public function index(): Response
    {
        return $this->render('conference/index.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }

    #[Route('/produit/produit/{id}', name: 'Produit')]
    public function produit(): Response
    {
        return $this->render('conference/produit.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/commande', name: 'Commande')]
    public function commande(): Response
    {
        return $this->render('conference/commande.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

}
