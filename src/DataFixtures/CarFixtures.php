<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Car;
use App\Entity\Image;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CarFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker =Factory::create('Fr-fr');
        for($c = 1; $c <=9; $c++){

            $car = new Car();
            $slugify =new Slugify();

            $marque = $faker->lexify('constructor ???');
            $fcar = $faker->lexify('car ??????');
            $slug =$slugify->slugify($marque.'-'.$fcar.'-'.rand(1,100000));
            $date = $faker->dateTime($max = 'now', $timezone = null);
            $description =$faker->paragraph(3);
            $option ='<p>'.join('</p><p>',$faker->sentences(rand(5,15), false)).'</p>';
            $carburant = ['essence','diesel'];
            $transmission = [2,4];

            $car->setMarque($marque)
                ->setModele($fcar)
                ->setCover('https://placekitten.com/1000/350')
                ->setKm(rand(0,1000000))
                ->setPrix(rand(1000,100000))
                ->setProprios(rand(1,10))
                ->setCylindre(rand(800,8000))
                ->setPuissance(rand(10,2000))
                ->setCarburant($faker->randomElement($carburant))
                ->setDateMiseCirculation($date)
                ->setTransmission($faker->randomElement($transmission))
                ->setDescription($description)
                ->setOptions($option)
                ->setSlug($slug);

                $manager->persist($car);

                for($i=1; $i <= rand(2,5); $i++){
                    $image = new Image();
                    $image->setUrl('https://placekitten.com/350/350')
                        ->setCaption($faker->sentence())
                        ->setCar($car);
                    $manager->persist($image);    
                }
        }
        $manager->flush();
    }
}
