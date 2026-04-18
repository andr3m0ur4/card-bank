<?php

namespace CardCollection\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MigrationsControllerTest extends WebTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $container = static::getContainer();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine')->getManager();
        $entityManager->getConnection()->executeStatement('DROP SCHEMA public CASCADE; CREATE SCHEMA public;');
        self::ensureKernelShutdown();
    }

    public function testGet(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/migrations');

        $content = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertJson($content);
        $parsedContent = json_decode($content, true);
        $this->assertIsArray($parsedContent);

        $this->assertArrayHasKey('executed', $parsedContent);

        if ($parsedContent['executed']) {
            $this->assertArrayHasKey('migrations', $parsedContent);

            $migrations = $parsedContent['migrations'];
            $this->assertIsArray($migrations);
            $this->assertGreaterThan(0, count($migrations));
        } else {
            $this->assertArrayHasKey('message', $parsedContent);
            $this->assertIsString($parsedContent['message']);
            $this->assertEquals('No pending migrations to execute.', $parsedContent['message']);
        }
    }

    public function testPost(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/v1/migrations');

        $content = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertJson($content);
        $parsedContent = json_decode($content, true);
        $this->assertIsArray($parsedContent);

        $this->assertArrayHasKey('executed', $parsedContent);

        if ($parsedContent['executed']) {
            $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
            $this->assertArrayHasKey('migrations', $parsedContent);

            $migrations = $parsedContent['migrations'];
            $this->assertIsArray($migrations);
            $this->assertGreaterThan(0, count($migrations));
        } else {
            $this->assertArrayHasKey('message', $parsedContent);
            $this->assertIsString($parsedContent['message']);
            $this->assertEquals('No pending migrations to execute.', $parsedContent['message']);
        }
    }
}
