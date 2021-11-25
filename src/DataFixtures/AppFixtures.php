<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(['email' => 'test@test.com', 'password' => 'test']);
        UserFactory::createMany(10);

        $manager->flush();
    }
}
