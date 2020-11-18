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

}
