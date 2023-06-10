<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// #[Route('', name: 'homepage_')]
class HomepageController extends AbstractController
{
    #[Route('/', name: 'play')]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'button' => 'Jouer !',
            'title' => false
        ]);
    }

    #[Route('/perdu', name: 'lost')]
    public function lostGame(): Response
    {
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'button' => 'Rejouer !',
            'title' => 'Vous avez perdu !'
        ]);
    }

    #[Route('/gagne', name: 'win')]
    public function winGame(): Response
    {
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'button' => 'Rejouer !',
            'title' => 'Vous avez gagné !'
        ]);
    }
}
