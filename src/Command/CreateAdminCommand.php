<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; //Rajout de la définition

#[AsCommand(
    name: 'CreateAdmin',
    description: 'Add a short description for your command',
)]
class CreateAdminCommand extends Command
{   
    private UserPasswordHasherInterface $passwordHasher; //Nouvelle propriété
    private ManagerRegistry $doctrine;
    
    protected function configure(): void
    {
        /*$this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;*/

        $this->addArgument('login', InputArgument::REQUIRED, 'A quoi sert ce paramètre')
             ->addArgument('password', InputArgument::REQUIRED, 'A quoi sert ce paramètre');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //retrieve the argument value using getArgument()
        $login = $input->getArgument('login');
        $admin = new User();
        $admin->setLogin($login);
        $password = $input->getArgument('password'); //Récupe2ration du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword 
        (   //cryptage du mot de passe
            $admin,
            $password

        );
        $admin->setPassword($hashedPassword); //Mot de passe mis dans l'instance
        
        //Récupérer le gestionnaire des entités de doctrine
        $entityManager=$this->doctrine->getManager();
        //Dire à Doctrine de marque l'entité comme "à persister"
        $entityManager->persist($admin);
        //Dire à Doctrine de flusher
        //(écrire en base toutes les entitées marqués comme à persister)
        $entityManager->flush();
        /*$io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;*/

        $output->writeln([
            'User '.$input->getArgument('login').' created',
            '===============',
            '',
        ]);
        return Command::SUCCESS;    
    }
    
    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher)
    {
        $this->doctrine = $doctrine;
        $this->passwordHasher = $passwordHasher; //on lie la propriété de la classe au wire
        parent::__construct();
    }

  
}
