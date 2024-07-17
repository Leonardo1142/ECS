<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        $user = (new User)
            ->setEmail('admin@test.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setFirstName('Leonardo')
            ->setLastName('Courtois')
            ->setPassword(
                $this->hasher->hashPassword(
                    new User,
                    'Test1234!'
                )
            );
        $manager->persist($user);
        for ($i = 0; $i < 10; $i++) {
            $user = (new User)
                ->setEmail($this->faker->unique()->email())
                ->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setRoles(
                    $this->faker->randomElements([
                        'ROLE_USER',
                        'ROLE_ADMIN',
                        'ROLE_EDITOR'
                    ], null)
                )
                ->setPassword(
                    $this->hasher->hashPassword(
                        new User,
                        'Test1234!'
                    )
                );
            $manager->persist($user);
        }
        $manager->flush();
    }
}
