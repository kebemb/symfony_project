<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Bien;
use App\Entity\FoodTruck;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 10; $i++){
            $ville = new Ville();
        $ville->setNom($faker->city);
        $ville->setCodePostal($faker->postcode);
        $ville->setSuperficie('3500');
        $manager->persist($ville);
    }
        $foodtruck = new  FoodTruck();
        $foodtruck->setNom('Mbayang Truck');
        $foodtruck->setTypeCuisine('Saloum');
        $manager->persist($foodtruck);

        $bien = new Bien();
        $bien->setNom('bien');;
        $bien->setDescription('description');
        $bien->setDateDispo(new \DateTime('2023-12-12'));
        $bien->setPrix('400000');
        $bien->setVille($ville);
        $bien->setAvecJardin(true);
        $manager->persist($bien);

        $manager->flush();
    }
}
