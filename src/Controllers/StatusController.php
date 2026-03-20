<?php

namespace CardCollection\Controllers;

use CardCollection\Infra\Database;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatusController
{
    public function showStatus(): JsonResponse
    {
        $updatedAt = (new DateTime())->format('Y-m-d H:i:s');

        $entityManager = Database::getEntityManager();
        $databaseVersion = $entityManager->getConnection()->getServerVersion();

        $databaseMaxConnections = intval($entityManager->getConnection()->fetchOne('SHOW max_connections'));

        $databasename = $_ENV['POSTGRES_DB'];
        $databaseOpenedConnections = $entityManager->getConnection()->fetchOne("SELECT count(*)::int FROM pg_stat_activity WHERE datname = :databaseName", ['databaseName' => $databasename]);

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
