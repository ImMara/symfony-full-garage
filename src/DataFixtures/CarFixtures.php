<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Car;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CarFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker =Factory::create('Fr-fr');
        for($i = 1; $i <=9; $i++){
            $car = new Car();
            $marque = $faker->lexify('constructor ???');
            $fcar = $faker->lexify('car ??????');
            $coverImage =$faker->imageUrl(200,200);
            $date = $faker->dateTime($max = 'now', $timezone = null);
            $description =$faker->paragraph(3);
            $option ='<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>';

            $car->setMarque($marque)
                ->setModele($fcar)
                ->setCover($coverImage)
                ->setKm(mt_rand(0,1000000))
                ->setPrix(mt_rand(1000,1000000))
                ->setProprios(mt_rand(1,10))
                ->setCylindre(mt_rand(800,8000))
                ->setPuissance(mt_rand(10,2000))
                ->setCarburant('lorem')
                ->setDateMiseCirculation($date)
                ->setTransmission(mt_rand(2,4))
                ->setDescription($description)
                ->setOptions($option);
                $manager->persist($car);
        }
        $manager->flush();
    }
}
