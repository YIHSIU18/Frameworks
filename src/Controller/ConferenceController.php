<?php

namespace App\Controller;

//Defeniction
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Commande;
use App\Repository\ProduitRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

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
    	return new RedirectResponse('/login/form');
    }

    #[Route("/user/create", name:"user_create")]
    public function createUserAction(Request $request): Response
    {
        $monObjet = new User(); // ATTENTION mettre VOTRE classe user
        $form = $this->createFormBuilder($monObjet)
    	// On ajoute les champs adresse, cp, ville, etc…
    	->add('login', TextType::class)
        ->add('password', TextType::class)
        ->add('address', TextType::class)
        ->add('codeposte', TextType::class)
        ->add('ville', TextType::class)
        ->add('nom', TextType::class)
        ->add('prenom', TextType::class)
        ->add('roles', TextType::class)
	    ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
    	->getForm();
        
        $form->handleRequest($request); //Charge les informations de la requeste pour voir si le formulaire a été soumis
        if ($form->isSubmitted() && $form->isValid()) //A ajouter avant le render
        {
            $user = $form->getData(); //On récupère l’objet
            $user->setRoles(['ROLE_USER']); //On force l’utilisateur à être un ROLE_USER normal
            $em = $this->doctrine->getManager();    
                    try {
            $em->persist($user); //La Sauvegarde
            $em->flush();  	 
            return new RedirectResponse('/');
                    } catch (UniqueConstraintViolationException $e){
                          $form->get('login')->addError(new FormError('Identifiant déjà utilisé')); //Mettre le bon champ identifiant
                    }
        }
            

        return $this->render('conference/createUser.html.twig', array(
        	'form' => $form->createView(), // on le passe au template

        ));
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

    #[Route('/commande/produit/{id}/new', name:'app_commandProduit')]
    public function commandeProduit(Request $request,EntityManagerInterface $em, $id):Response
    {
        //Récupère le produit par son id
        $commandeProduit = $this->produitRepository->find(['id'=>$id]);
        //on crée une commande, avec ce produit et on y met aussi l’utilisateur
        $commande = new Commande();
        $commande->setUser($this->security->getUser());
        $commande->setDate(new \DateTime());
        $commande->setEtat("en cours");
        $commande->setProduit($commandeProduit);
        
        $em->persist($commande); //La Sauvegarde
        $em->flush();  	 
    
        $this->AddFlash
        (
            'succes',
            "Le produit a bien été enregistré!"
        );        
        //Redirige la page home
        return $this->index();
    }

    #[Route('/commande', name: 'app_commande')]
    public function commande(): Response
    {
        return $this->render('conference/commande.html.twig', [
            'controller_name' => 'ConferenceController',
        ]);
    }

}
