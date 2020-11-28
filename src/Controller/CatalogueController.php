<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Image;
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

            foreach($car->getImages() as $image){
                $image->setCar($car);
                $manager->persist($image);
            }
            $car->setAuthor($this->getUser());
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

    /**
     * car details
     * @route("/catalogue/{slug}",name="cars_show")
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
     * Formulaire d'edition
     * @route("catalogue/{slug}/edit", name="car_edit")
     * @param Car $car
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Car $car, Request $request,EntityManagerInterface $manager)
    {
        $form2 = $this->createForm(CatalogueType::class,$car);
        $form2->handleRequest($request);

        if($form2->isSubmitted() && $form2->isValid())
        {
            foreach($car->getImages() as $image){
               $image->setCar($car);
               $manager->persist($image);
            }
            $manager->persist($car);
            $manager->flush();
            $this->addFlash(
              'success',
                    "l'annonce<strong>{$car->getSlug()}</strong> a bien été modifier"
            );
            return $this->redirectToRoute('cars_show',[
                'slug'=>$car->getSlug()
            ]);
        }

        return $this->render('catalogue/edit.html.twig',
        [
            'myForm' => $form2->createView()
        ]);
    }
}
