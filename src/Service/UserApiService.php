<?php

namespace App\Service;

use App\Entity\AgeName;
use App\Entity\City;
use App\Entity\OperativeSystem;
use App\Entity\Summary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;



class UserApiService
{
    private $httpClient;
    private $logger;
    private $em;


    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->em = $em;
    }

    public function getUsers(): array
    {
        $response = $this->httpClient->request('GET', 'https://dummyjson.com/users');
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error en obtener la informacion de la API');
        }
        return $response->toArray();
    }

    public function saveUsersToJson(array $users, string $directory): void
    {
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new \RuntimeException(sprintf('La carpeta no ha sido creada ', $directory));
            }
        }

        $date = new \DateTime();
        $jsonFilename = $directory . '/data_' . $date->format('Ymd') . '.json';
        $jsonData = json_encode($users, JSON_PRETTY_PRINT);

        if (file_put_contents($jsonFilename, $jsonData) === false) {
            throw new \Exception('Error en el archivo ' . $jsonFilename);
        }
    }

    public function convertJsonToCsv(string $jsonFilePath, string $directory): void
    {
        $jsonData = file_get_contents($jsonFilePath);
        $decodedData = json_decode($jsonData, true);

        if (empty($decodedData) || !isset($decodedData['users'])) {
            throw new \Exception('data no encontrada');
        }

        $users = $decodedData['users'];

        if (empty($users)) {
            throw new \Exception('data no encontrada');
        }

        $date = new \DateTime();
        $csvFilename = $directory . '/data_' . $date->format('Ymd') . '.csv';

        $csvFile = fopen($csvFilename, 'w');

        $headersWritten = false;
        foreach ($users as $user) {
            $user = $this->flattenUserData($user);

            if (!$headersWritten) {
                fputcsv($csvFile, array_keys($user));
                $headersWritten = true;
            }

            fputcsv($csvFile, $user);
        }

        fclose($csvFile);

        $this->logger->info('Datos convertidos a CSV y guardados correctamente', [
            'csv_filename' => $csvFilename,
            'total_records' => count($users),
        ]);
    }

    public function summary(string $jsonFilePath, string $directory): void
    {
        $jsonData = file_get_contents($jsonFilePath);
        $decodedData = json_decode($jsonData, true);

        if (empty($decodedData) || !isset($decodedData['users'])) {
            throw new \Exception('Error en el archivo json');
        }

        $users = $decodedData['users'];

        $ageRanges = [
            '00-10' => [0, 10],
            '11-20' => [11, 20],
            '21-30' => [21, 30],
            '31-40' => [31, 40],
            '41-50' => [41, 50],
            '51-60' => [51, 60],
            '61-70' => [61, 70],
            '71-80' => [71, 80],
            '81-90' => [81, 90],
            '91+' => [91, PHP_INT_MAX]
        ];

        $ageCounts = [];
        foreach ($ageRanges as $range => $limits) {
            $ageCounts[$range] = [
                'male' => 0,
                'female' => 0,
                'other' => 0,
            ];
        }

        $femaleCount = 0;
        $maleCount = 0;
        $otherCount = 0;

        $cityCounts = [];
        $osCounts = [
            'Windows' => 0,
            'Macintosh' => 0,
            'Linux' => 0,
        ];

        foreach ($users as $user) {
            if (!isset($user['gender']) || !isset($user['age']) || !isset($user['address']['city']) || !isset($user['userAgent'])) {
                throw new \RuntimeException('Valores no encontrados');
            }

            $age = $user['age'];
            $gender = $user['gender'];
            $city = $user['address']['city'];
            $userAgent = $user['userAgent'];

            if ($gender === 'female') {
                $femaleCount++;
            } elseif ($gender === 'male') {
                $maleCount++;
            } elseif ($gender === 'other') {
                $otherCount++;
            }

            foreach ($ageRanges as $range => $limits) {
                if ($age >= $limits[0] && $age <= $limits[1]) {
                    $ageCounts[$range][$gender]++;
                    break;
                }
            }

            if (!isset($cityCounts[$city])) {
                $cityCounts[$city] = [
                    'male' => 0,
                    'female' => 0,
                    'other' => 0,
                ];
            }
            $cityCounts[$city][$gender]++;

            if (stripos($userAgent, 'Windows') !== false) {
                $osCounts['Windows']++;
            } elseif (stripos($userAgent, 'Macintosh') !== false) {
                $osCounts['Macintosh']++;
            } elseif (stripos($userAgent, 'Linux') !== false) {
                $osCounts['Linux']++;
            }
        }

        $totalUsers = count($users);

        $date = new \DateTime();
        $csvFilename = $directory . '/summary_' . $date->format('Ymd') . '.csv';
        $csvFile = fopen($csvFilename, 'w');

        if ($csvFile === false) {
            throw new \RuntimeException('Error en la creacion del archivo');
        }

        fputcsv($csvFile, ['register', 'total']);
        fputcsv($csvFile, ['total_records', $totalUsers]);
        fputcsv($csvFile, ['male', $maleCount]);
        fputcsv($csvFile, ['female', $femaleCount]);
        fputcsv($csvFile, ['other', $otherCount]);

        fputcsv($csvFile, []);

        fputcsv($csvFile, ['age', 'male', 'female', 'other']);
        foreach ($ageCounts as $range => $counts) {
            fputcsv($csvFile, [
                $range,
                $counts['male'],
                $counts['female'],
                $counts['other'],
            ]);
        }

        fputcsv($csvFile, []);

        fputcsv($csvFile, ['city', 'male', 'female', 'other']);
        foreach ($cityCounts as $city => $counts) {
            fputcsv($csvFile, [
                $city,
                $counts['male'],
                $counts['female'],
                $counts['other'],
            ]);
        }

        fputcsv($csvFile, []);

        fputcsv($csvFile, ['OS', 'total']);
        foreach ($osCounts as $os => $count) {
            fputcsv($csvFile, [$os, $count]);
        }

        fclose($csvFile);

        $this->logger->info('Datos Convertidos y guardados en CSV', [
            'csv_filename' => $csvFilename,
            'total_records' => $totalUsers,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'otherCount' => $otherCount,
            'age_counts' => $ageCounts,
            'city_counts' => $cityCounts,
            'os_counts' => $osCounts,
        ]);
        $summary = new Summary();
        $summary->setTotalRecords($totalUsers);
        $summary->setMaleCount($maleCount);
        $summary->setFemaleCount($femaleCount);
        $summary->setOtherCount($otherCount);
        $this->em->persist($summary);
        $this->em->flush();

        foreach ($ageCounts as $range => $counts) {
            $ageRange = new AgeName();
            $ageRange->setAgeRange($range);
            $ageRange->setMaleCount($counts['male']);
            $ageRange->setFemaleCount($counts['female']);
            $ageRange->setOtherCount($counts['other']);
            $this->em->persist($ageRange);
        }
        $this->em->flush();

        foreach ($cityCounts as $city => $counts) {
            $cityEntity = new City();
            $cityEntity->setCity($city);
            $cityEntity->setMaleCount($counts['male']);
            $cityEntity->setFemaleCount($counts['female']);
            $cityEntity->setOtherCount($counts['other']);
            $this->em->persist($cityEntity);
        }
        $this->em->flush();

        foreach ($osCounts as $os => $count) {
            $osEntity = new OperativeSystem();
            $osEntity->setOs($os);
            $osEntity->setTotalCount($count);
            $this->em->persist($osEntity);
        }
        $this->em->flush();
    }

    private function flattenUserData(array $user): array
    {
        $user['hair'] = json_encode($user['hair']);
        $user['address'] = json_encode($user['address']);
        $user['bank'] = json_encode($user['bank']);
        $user['company'] = json_encode($user['company']);
        $user['crypto'] = json_encode($user['crypto']);
        return $user;
    }
}
