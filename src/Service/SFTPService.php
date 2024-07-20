<?php

namespace App\Service;

use phpseclib3\Net\SFTP;

class SFTPService
{
    private $sftp;

    public function __construct(string $host, int $port, string $username, string $password)
    {
        $this->sftp = new SFTP($host, $port);

        if (!$this->sftp->login($username, $password)) {
            throw new \Exception('Error de Login');
        }
    }

    public function uploadFile(string $localFilePath, string $remoteFilePath): bool
    {
        return $this->sftp->put($remoteFilePath, $localFilePath, SFTP::SOURCE_LOCAL_FILE);
    }
}
