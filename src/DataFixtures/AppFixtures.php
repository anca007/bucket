<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
   // private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
        //$this->userPasswordHasher = $this->userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->addUsers(30, $manager);
        $this->addCategories($manager);
        $this->addWishes(30, $manager);
    }

    private function addUsers(int $number, ObjectManager $manager){
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < $number; $i++) {

            $user = new User();
            $user
                ->setRoles(["ROLE_USER"])
                ->setEmail($faker->email)
                ->setUsername($faker->userName)
                ->setPassword(
                    $this->userPasswordHasher->hashPassword($user, "123")
                );

            $manager->persist($user);
        }

        $manager->flush();

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
        $users = $manager->getRepository(User::class)->findAll();

        for ($i = 0 ; $i < $number; $i++){

            $wish = new Wish();

            $wish
                ->setTitle(implode(" ", $faker->words()))
                ->setUser($faker->randomElement($users))
                ->setIsPublished($faker->boolean(70))
                ->setDateCreated($faker->dateTimeBetween(new \DateTime("-1 year")))
                ->setDescription($faker->sentence)
                ->setCategory($faker->randomElement($categories));

            $manager->persist($wish);
        }

        $manager->flush();
    }
}
