<?php

namespace CardCollection\Tests\Controllers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatusControllerTest extends WebTestCase
{
    public function testShowStatus()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/status');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }
}
