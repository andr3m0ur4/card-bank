<?php

namespace CardCollection\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class StatusController extends AbstractController
{
    #[Route('/api/v1/status', name: 'show_status')]
    public function showStatus(EntityManagerInterface $entityManager): JsonResponse
    {
        $updatedAt = (new \DateTime())->format('Y-m-d H:i:s');

        $databaseVersion = $entityManager->getConnection()->getServerVersion();

        $databaseMaxConnections = intval($entityManager->getConnection()->fetchOne('SHOW max_connections'));

        $databasename = $_ENV['POSTGRES_DB'];
        $databaseOpenedConnections = $entityManager->getConnection()->fetchOne('SELECT count(*)::int FROM pg_stat_activity WHERE datname = :databaseName', ['databaseName' => $databasename]);

        return new JsonResponse([
            'updated_at' => $updatedAt,
            'dependencies' => [
                'database' => [
                    'version' => $databaseVersion,
                    'max_connections' => $databaseMaxConnections,
                    'opened_connections' => $databaseOpenedConnections,
                ],
            ],
        ]);
    }
}
