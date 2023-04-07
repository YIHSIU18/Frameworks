<?php

namespace App\Controller;

//Defeniction
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConferenceController extends AbstractController
{
    //proprietaire
    private ProduitRepository $produitRepository;
    private Security $security;
    //Consturteur
    public function __construct(ProduitRepository $produitRepository, Security $security)
    {
        $this->produitRepository = $produitRepository;
        $this->security = $security;
       // parent::__construct();


    }

    #[Route('/login/check', name: 'app_check')]
    public function check(): Response
    {
         #Si on arrive pas à récupérer l’utilisateur, on est renvoyés vers le login
         $user = $this->security->getUser();
         #Si tout se passe bien on est renvoyé à la base du site, mais loggué
         return $this->redirectToRoute('/');   	 
    }

    #[Route('/login/form', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response 
    {	 
  	    //Récupère l’erreur d’authentification (s’il y en a une)
    	$error = $authenticationUtils->getLastAuthenticationError();
   	    //Récupère le dernier login d’utilisateur donné (s’il y en a un)
    	$lastUsername = $authenticationUtils->getLastUsername();
    	return $this->render('conference/login.html.twig', [
        	'last_username' => $lastUsername,
        	'error'     	=> $error,
    	]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response 
    {	 
    	//On demande à la sécurité de se déloguer
        $this->security->logout();
        //Si tout se passe bien on est renvoyé à la base du site, délogué
    	return new RedirectResponse('/');
    }



    #[Route('/', name: 'app_home')]//Annotation qui permet de définir la route
    public function index(): Response
    {
        $listeObjet = $this->produitRepository->findAll();
        return $this->render('conference/index.html.twig', [//Rend cette page là
            'controller_name' => 'ConferenceController',
            'liste' =>$listeObjet
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit')]
    public function produit($id): Response
    {
        $produit = $this->produitRepository->find(['id'=>$id]);
        return $this->render('conference/produit.html.twig', [
            'controller_name' => 'ConferenceController',
            'produit' => $produit
        ]);
    }

    #[Route('/commande', name: 'app_commande')]
    public function commande(): Response
    {
        return $this->render('conference/commande.html.twig', [
            'controller_name' => 'ConferenceController',
        ]);
    }

}
