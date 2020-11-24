<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatalogueController extends AbstractController
{
    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function index(CarRepository $repo): Response
    {
        $cars = $repo->findAll();
        return $this->render('catalogue/index.html.twig', [
            'cars' => $cars
        ]);
    }
    /**
     * car details
     * @route("/cars/{slug}",name="cars_show")
     *
     * @param car $car
     * @return Response
     */
    public function show(Car $car){
        return  $this->render('catalogue/car.html.twig',[
            'car'=>$car
        ]);
    }
}
