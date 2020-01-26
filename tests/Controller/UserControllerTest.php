<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testStoreUser()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/user', [
            "email" => "mateus@mateus.com",
            "name" => "Mateus Soares",
            "password" => "123456789"
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testWithouSendingEmail()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/user');

        $this->assertResponseStatusCodeSame(400);
    }
    
    public function testWithouSendingValidEmail()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/user', [
            "email" => "mateus"
        ]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testWithouSendingName()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/user', [
            "email" => "mateus@mateus.com"
        ]);

        $this->assertResponseStatusCodeSame(400);
    }
    
    public function testWithouSendingPassword()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/user', [
            "email" => "mateus@mateus.com",
            "name" => "Mateus Soares",
        ]);

        $this->assertResponseStatusCodeSame(400);
    }

}