<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create a user',
)]
class CreateUserCommand extends Command
{
    public function __construct(private EntityManagerInterface $em, private UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('username', null, InputOption::VALUE_OPTIONAL, 'Username')
            ->addOption('email', null, InputOption::VALUE_OPTIONAL, 'Email address')
            ->addOption('password', null, InputOption::VALUE_OPTIONAL, 'Password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        $username = $input->getOption('username') ?? $io->ask('Username');
        $email = $input->getOption('email') ?? $io->ask('Email address');
        $password = $input->getOption('password') ?? $io->askHidden('Password (your typing will be hidden)');

        $output->writeln([
            'User details to create in database:',
            'Username: ' . $username,
            'Email: ' . $email,
            '',
        ]);

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);

        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (\Exception $e) {
            $io->error('Error creating user: ' . $e->getMessage());
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}