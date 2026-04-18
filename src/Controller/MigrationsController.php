<?php

namespace CardCollection\Controller;

use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\MigratorConfiguration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class MigrationsController extends AbstractController
{
    #[Route('/api/v1/migrations', name: 'migrations', methods: ['GET', 'POST'])]
    public function index(Request $request, DependencyFactory $dependencyFactory): JsonResponse
    {
        $method = $request->getMethod();

        $metadataStorage = $dependencyFactory->getMetadataStorage();
        $metadataStorage->ensureInitialized();

        $statusCalculator = $dependencyFactory->getMigrationStatusCalculator();
        $pending = $statusCalculator->getNewMigrations();

        if (count($pending) === 0) {
            return $this->json([
                'executed' => false,
                'message' => 'No pending migrations to execute.',
            ]);
        }

        $migrations = [];
        $migrator = $dependencyFactory->getMigrator();
        $config = new MigratorConfiguration();
        $availableMigrations = $dependencyFactory->getMigrationRepository()->getMigrations();
        $items = $availableMigrations->getItems();
        $lastMigration = end($items);

        $plan = $dependencyFactory
            ->getMigrationPlanCalculator()
            ->getPlanUntilVersion($lastMigration->getVersion());

        if ($method === 'GET') {
            $config->setDryRun(true);
            $result = $migrator->migrate($plan, $config);

            foreach ($result as $queries) {
                $migrations[] = [
                    'statement' => $queries[0]->getStatement(),
                    'parameters' => $queries[0]->getParameters(),
                    'types' => $queries[0]->getTypes(),
                ];
            }
            return $this->json([
                'executed' => true,
                'migrations' => $migrations,
            ]);
        }

        $config->setDryRun(false);
        $result = $migrator->migrate($plan, $config);

        foreach ($result as $key => $queries) {
            $migrations[] = [
                'migration' => $key,
            ];
        }

        return $this->json(
            [
                'executed' => true,
                'migrations' => $migrations,
            ],
            JsonResponse::HTTP_CREATED,
        );
    }
}
