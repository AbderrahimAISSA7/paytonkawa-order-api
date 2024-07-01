<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiOrderTest extends WebTestCase
{
    public function testGetOrders()
    {
        $client = static::createClient();

        $client->request('GET', '/api/orders');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCreateOrder()
    {
        $client = static::createClient();

        $client->request('POST', '/api/orders', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'order_number' => 'ORD-123',
            'total_amount' => 100.50,
            'status' => 'pending'
        ]));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    // Ajoutez d'autres tests pour les autres endpoints
}
