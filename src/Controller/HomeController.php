<?php

namespace App\Controller;

use App\Entity\About;
use App\Entity\Car;
use App\Repository\CarRepository;
use App\Repository\AboutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */

    public function index(): Response
    {
        $myLimit=3;
        $carRepo = $this->getDoctrine()->getRepository(Car::class);
        $cars = $carRepo->findBy([],["id"=>"DESC"],$myLimit,null);

        $aboutRepo = $this->getDoctrine()->getRepository(About::class);
        $about = $aboutRepo->findAll();
//        var_dump($about);

        return $this->render('home/index.html.twig', [
            'cars' => $cars,
            'about' =>$about,
        ]);
    }
}
