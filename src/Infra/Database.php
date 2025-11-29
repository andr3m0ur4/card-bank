<?php

namespace CardCollection\Infra;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class Database
{
    private EntityManager $entityManager;

    private function __construct()
    {
    }

    public static function getEntityManager(): EntityManager
    {
        $instance = new self();
        $instance->connect();
        return $instance->entityManager;
    }

    private function connect(): EntityManager {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__ . '/src'],
            isDevMode: true,
        );

        $connection = DriverManager::getConnection([
            'driver' => 'pdo_pgsql',
            'host' => $_ENV['POSTGRES_HOST'],
            'port' => $_ENV['POSTGRES_PORT'],
            'dbname' => $_ENV['POSTGRES_DB'],
            'user' => $_ENV['POSTGRES_USER'],
            'password' => $_ENV['POSTGRES_PASSWORD'],
        ], $config);

        $this->entityManager = new EntityManager($connection, $config);
        return $this->entityManager;
    }
}
