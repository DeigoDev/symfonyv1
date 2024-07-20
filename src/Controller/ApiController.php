<?php

namespace App\Controller;

use App\Service\UserApiService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    private $userApiService;

    public function __construct(UserApiService $userApiService)
    {
        $this->userApiService = $userApiService;
    }

    #[Route('/index', methods: ['GET'], name: 'api_users')]
    public function index(LoggerInterface $logger): JsonResponse
    {
        try {
            $users = $this->userApiService->getUsers();
            $directory = $this->getParameter('kernel.project_dir') . '/public';
            $this->userApiService->saveUsersToJson($users, $directory);
            $jsonFilename = $directory . '/data_' . (new \DateTime())->format('Ymd') . '.json';
            $this->userApiService->convertJsonToCsv($jsonFilename, $directory);
            $this->userApiService->summary($jsonFilename, $directory);

            return $this->json(['message' => 'Data Guardada exitosamente']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
