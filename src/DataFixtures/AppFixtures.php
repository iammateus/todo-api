<?php

namespace App\DataFixtures;

use App\Entity\Client\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\SodiumPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * @var SodiumPasswordEncoder
     */
    private $encoder;

    public function __construct()
    {
        $this->encoder = new SodiumPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $client = Client::create("Todo-app");
        $client->setRedirect("http://localhost:3000");
        $client->setActive(true);

        $encoded = $this->encoder->encodePassword("24cdddf008e9814958c0ad2788973fae159222c8b06d755d91c2da33108feb16", null);
        $client->setSecret($encoded);

        $manager->persist($client);
        $manager->flush($client);
    }
}
