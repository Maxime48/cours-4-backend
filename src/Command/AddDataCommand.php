<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cours;
use Faker\Factory;

#[AsCommand(
    name: 'app:add-data',
    description: 'Add fake data to the database',
)]
class AddDataCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Create a new Faker generator
        $faker = Factory::create();

        // Create a new instance of your entity and set its properties using Faker
        $cours = new Cours();
        $cours->setNom($faker->words(3, true));
        $cours->setProfesseur($faker->name);
        $cours->setDuree($faker->randomDigitNotNull . ' hour');

        // Persist and flush the entity to the database
        $this->entityManager->persist($cours);
        $this->entityManager->flush();

        $io->success('Fake data added successfully!');

        return Command::SUCCESS;
    }
}
