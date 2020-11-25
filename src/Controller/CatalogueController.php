<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CatalogueType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * fonction d'ajout d'une annonce de voiture
     * @route("/catalogue/new", name="new")
     *
     * @param CatalogueType $car
     * @return Response
     */
    public function news (EntityManagerInterface $manager, Request $request)
    {
        $car = new Car();
        $form = $this->createForm(CatalogueType::class , $car);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($car);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$car->getSlug()}</strong> a bien été enregistrée"
            );

            return $this->redirectToRoute('cars_show',[
                'slug' => $car->getSlug()
            ]);
        }
        return $this->render('catalogue/new.html.twig' , [
            'myForm' => $form->createView()
        ]);
    }
}
