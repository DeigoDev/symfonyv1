<?php

namespace App\Command;

use App\Service\UserApiService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:save-users-data',
    description: 'Add a short description for your command',
)]
class SaveUsersDataCommand extends Command
{
    private $logger;
    private $userApiService;
    
    public function __construct(LoggerInterface $logger, UserApiService $userApiService)
    {
        $this->logger = $logger;
        $this->userApiService = $userApiService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Guarda la informacion de la API en archivos csv.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $users = $this->userApiService->getUsers();
            $directory = $this->getParameter('kernel.project_dir') . '/public';
            $this->userApiService->saveUsersToJson($users, $directory);
            $jsonFilename = $directory . '/data_' . (new \DateTime())->format('Ymd') . '.json';
            $this->userApiService->convertJsonToCsv($jsonFilename, $directory);
            $this->userApiService->summary($jsonFilename, $directory);
            $io->success('Data Guardada');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
