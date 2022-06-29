<?php

namespace App\DataFixtures;

use App\Factory\ArticleFactory;
use App\Factory\EventFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(['email' => 'test@test.com', 'password' => 'test']);
        UserFactory::createMany(10);
        TagFactory::createMany(10);
        EventFactory::createMany(10);
        ArticleFactory::createMany(10);

        $manager->flush();
    }
}
