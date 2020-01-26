<?php

namespace App\DataFixtures;

use App\Entity\Client\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $client = Client::create("Todo-app");
        $client->setSecret("24cdddf008e9814958c0ad2788973fae159222c8b06d755d91c2da33108feb16");
        $client->setRedirect("http://localhost:3000");
        $client->setActive(true);

        $manager->persist($client);
        $manager->flush($client);
    }
}
