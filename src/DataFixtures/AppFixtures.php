<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("ahlem.smayer@gmail.com");
        $user->setPassword("esprit");
        $manager->persist($user);

        $manager->flush();
    }
}
