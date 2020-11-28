<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Image;
use App\Form\CatalogueType;
use App\Repository\CarRepository;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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

            $file = $form['cover']->getData();
            if(!empty($file)){
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                }
                catch(FileException $e)
                {
                    return $e->getMessage();
                }

                $car->setCover($newFilename);
            }


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
     * @Security("(is_granted('ROLE_USER') and user === car.getAuthor()) or is_granted('ROLE_ADMIN')", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
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
    /**
     * Permet de supprimer une annonce
     * @Route("/catalogue/{slug}/delete", name="delete")
     * @Security("is_granted('ROLE_USER') and user === car.getAuthor()", message="Vous n'avez pas le droit d'accèder à cette ressource")
     * @param Car $car
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Car $car, EntityManagerInterface $manager)
    {
        $this->addFlash(
            'success',
            "L'annonce <strong>{$car->getSlug()}</strong> a bien été supprimée"
        );
        $manager->remove($car);
        $manager->flush();
        return $this->redirectToRoute("catalogue");
    }


}
