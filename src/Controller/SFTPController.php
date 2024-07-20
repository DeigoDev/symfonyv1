<?php

namespace App\Controller;

use App\Service\SFTPService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SFTPController extends AbstractController
{
    private $sftpService;

    public function __construct(SFTPService $sftpService)
    {
        $this->sftpService = $sftpService;
    }

    #[Route('sftp_upload', name: 'upload')]
    public function upload(): Response
    {
        $localFilePath = 'summary_20240718.csv';
        $remoteFilePath = '#';

        if ($this->sftpService->uploadFile($localFilePath, $remoteFilePath)) {
            return new Response('Archivo Cargado');
        } else {
            return new Response('Error en la carga del archivo', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
