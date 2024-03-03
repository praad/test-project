<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const SIZE = 10;

    public function __construct(
        public UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= self::SIZE; $i++) {
            $user = new User();
            $user
                ->setFirstName('First name ' . $i)
                ->setLastName('Last name ' . $i)
                ->setUsername("test-user-$i")
                ->setEmail("test-$i-email@test.com")
                ->setPassword($this->passwordHasher->hashPassword($user, 'test_pass'));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
