<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        // Tüm şifreler: 'password'
        $user = new User();
        $user->setUsername("customer1");
        $user->setPassword('$2y$13$fyHQQ9tXx2GrBbe7FGGulOedq4Jjcd1RRWCEbXb4P3N5k/DBRnFq2');
        $manager->persist($user);

        $user = new User();
        $user->setUsername("customer2");
        $user->setPassword('$2y$13$fyHQQ9tXx2GrBbe7FGGulOedq4Jjcd1RRWCEbXb4P3N5k/DBRnFq2');
        $manager->persist($user);

        $user = new User();
        $user->setUsername("customer3");
        $user->setPassword('$2y$13$fyHQQ9tXx2GrBbe7FGGulOedq4Jjcd1RRWCEbXb4P3N5k/DBRnFq2');
        $manager->persist($user);

        $manager->flush();
    }
}
