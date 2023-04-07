<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Produit;

#[AsCommand(
    name: 'produits',
    description: 'Add a short description for your command',
)]
class ProduitsCommand extends Command
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        parent::__construct();
    }

    protected function configure(): void
    {
       
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityManager=$this->doctrine->getManager();
        for($i = 1; $i<=10; $i++)
        {
            $produit = new Produit();
            $produit
                ->setTitre('Pizza')
                ->setDescription('Une pizza magrita')
                ->setPrix(mt_rand(10,600));
            
            $entityManager->persist($produit);
        }
            $entityManager->flush();
        return Command::SUCCESS;
    }
}
