<?php

namespace CardCollection\Tests\Controllers;

use CardCollection\Kernel;
use PHPUnit\Framework\Attributes\After;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatusControllerTest extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    public function testShowStatus()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/status');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    /**
     * Ensures we clean up the error handler while shutdown.
     */
    #[After]
    public function __internalDisableErrorHandler(): void
    {
        restore_exception_handler();
    }
}
