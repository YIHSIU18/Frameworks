<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrivateController extends AbstractController
{
    #[Route('/admin', name: 'App_admin')]
    public function login(): Response
    {
        return $this->render('private/login.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/listing', name: 'App_listing')]
    public function listing(): Response
    {
        return $this->render('private/listing.html.twig', [
            'controller_name' => 'ListingController',
        ]);
    }

}
