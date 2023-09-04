<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addCategories($manager);
        $this->addWishes(30, $manager);
    }

    public function addCategories(ObjectManager $manager){

        $categories = ["Travel & Adventure", "Sport", "Entertainment", "Human Relations", "Others"];

        foreach ($categories as $val){

            $category = new Category();
            $category->setName($val);

            $manager->persist($category);
        }
        $manager->flush();
    }

    private function addWishes(int $number, ObjectManager $manager){

        $faker = Factory::create('fr_FR');
        $categories = $manager->getRepository(Category::class)->findAll();

        for ($i = 0 ; $i < $number; $i++){

            $wish = new Wish();

            $wish
                ->setTitle(implode(" ", $faker->words()))
                ->setAuthor($faker->firstName)
                ->setIsPublished($faker->boolean(70))
                ->setDateCreated($faker->dateTimeBetween(new \DateTime("-1 year")))
                ->setDescription($faker->sentence)
                ->setCategory($faker->randomElement($categories));

            $manager->persist($wish);
        }

        $manager->flush();
    }
}
