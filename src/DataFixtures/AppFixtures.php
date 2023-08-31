<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addWishes(30, $manager);
    }

    private function addWishes(int $number, ObjectManager $manager){

        $faker = Factory::create('fr_FR');

        for ($i = 0 ; $i < $number; $i++){

            $wish = new Wish();

            $wish
                ->setTitle(implode(" ", $faker->words()))
                ->setAuthor($faker->firstName)
                ->setIsPublished($faker->boolean(70))
                ->setDateCreated($faker->dateTimeBetween(new \DateTime("-1 year")))
                ->setDescription($faker->sentence);

            $manager->persist($wish);
        }

        $manager->flush();
    }
}
