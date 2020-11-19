<?php

namespace App\Controller;

use App\Entity\About;
use App\Repository\AboutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(AboutRepository $aboutRepo): Response
    {
        $about= $aboutRepo->findAll();

        return $this->render('contact/index.html.twig', [
            'about' => $about,
        ]);
    }
}
