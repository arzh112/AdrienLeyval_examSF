<?php

namespace App\Command;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:delete-user-finished-contract',
    description: 'Command to delete user who finished their contract',
)]
class DeleteUserFinishedContractCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository, 
        private EntityManagerInterface $em
    )
    {
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
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $users = $this->userRepository->findAll();
        $actualDate = new DateTime('now');

        foreach($users as $user) {
            if($user->getEndDate() != null && $user->getEndDate() < $actualDate) {
                $this->em->remove($user);
                $this->em->flush();
            }
        }

        $io->success('Les utilisateurs ont bien été supprimés');

        return Command::SUCCESS;
    }
}
