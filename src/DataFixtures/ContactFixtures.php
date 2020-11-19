<?php

namespace App\DataFixtures;

use App\Entity\About;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker =Factory::create('Fr-fr');
        $slugify = new Slugify();
        $adress=$faker->address;
        $description =$faker->sentence(135);
        $email=$faker->email;
        $contact = new About();

        $contact->setAdresse($adress)
                ->setDescription($description)
                ->setEmail($email);

        $manager->persist($contact);
        $manager->flush();
    }
}
