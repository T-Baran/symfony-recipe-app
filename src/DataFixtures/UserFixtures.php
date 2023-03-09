<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail("email@email.com");
        $user1->setUsername("Test");
        $user1->setPassword($this->hashPassword($user1, "password"));
        $manager->persist($user1);
        $manager->flush();
    }

    private function hashPassword($user, $password)
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }
}
