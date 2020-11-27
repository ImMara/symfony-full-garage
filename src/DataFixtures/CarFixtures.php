<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Car;
use App\Entity\Image;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CarFixtures extends Fixture
{
    // gestion du hash de password
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker =Factory::create('Fr-fr');
        // gestion des utilisateurs
        $users = []; // initialisation d'un tableau pour associer Ad et User
        $genres = ['male','femelle'];

        for($u=1; $u <= 10; $u++){
            $user = new User();
            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99).'.jpg';
            $picture .= ($genre == 'male' ? 'men/' : 'women/').$pictureId;

            $hash = $this->encoder->encodePassword($user,'password');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)).'</p>')
                ->setPassword($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[]= $user; // ajouter l'utilisateur fraichement créé dans le tableau pour l'association avec les annonces

        }

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
            $user = $users[rand(0,count($users)-1)];

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
                ->setSlug($slug)
                ->setAuthor($user)
            ;

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
