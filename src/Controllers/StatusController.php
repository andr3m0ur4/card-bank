<?php

namespace CardCollection\Controllers;

use CardCollection\Infra\Database;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatusController
{
    public function showStatus(): JsonResponse
    {
        $entityManager = Database::getEntityManager();
        error_log(json_encode($entityManager->getConnection()->fetchAssociative('SELECT 1 + :number AS sum', ['number' => 3])));
        return new JsonResponse(['status' => 'O André Moura é um bom dev PHP! 🚀']);
    }
}
