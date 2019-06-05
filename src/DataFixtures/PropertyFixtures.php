<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PropertyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i = 0; $i < 100; $i++)
        {
            $property = new Property();
            $property->setTitle($faker->words(2, true))
                    ->setSurface($faker->numberBetween($min=10, $max=400))
                    ->setPrice($faker->numberBetween($min=100000, $max=1000000))
                    ->setRooms($faker->numberBetween($min=1, $max=10))
                    ->setBedrooms($faker->numberBetween($min=1, $max=9))
                    ->setHeat($faker->numberBetween(0, count(Property::HEAT) - 1))
                    ->setFloor($faker->numberBetween($min=0, $max=15))
                    ->setAddress($faker->address)
                    ->setPostalCode($faker->postcode)
                    ->setCity($faker->city)
                    ->setSold(false)
                    ->setDescription($faker->sentences(3, true));

            $manager->persist($property);
        }
        $manager->flush();
    }
}
