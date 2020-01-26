<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testStoreUser()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/user');

        $this->assertResponseIsSuccessful();
    }
}