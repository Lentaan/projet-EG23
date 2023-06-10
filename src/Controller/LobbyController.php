<?php

namespace App\Controller;

use App\Entity\Lobby;
use App\Repository\LobbyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lobby', name: 'lobby_')]

class LobbyController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function index(Request $request, EntityManagerInterface $entity_manager, LobbyRepository $lobby_repository): Response
    {
        $lobbies = $lobby_repository->findAll();
        $lobby = new Lobby();
        $lobby->setNbPlayer(2);

        $form = $this->createFormBuilder($lobby)
          ->add('name', TextType::class, ['label' => 'Nom', 'attr' => ['placeholder' => 'Camille']])
          ->add('speciality', EnumType::class, ['class' => 'App\Enum\Speciality', 'label' => 'Spécialité', 'placeholder' => 'Choisir'])
          ->add('save', SubmitType::class, ['label' => 'Ajouter', 'attr' => ['class' => 'btn-create']])
          ->getForm();

        $join = $this->createFormBuilder($lobby)
          ->add('name', TextType::class, ['label' => 'Nom', 'attr' => ['placeholder' => 'Camille']])
          ->add('player_id', HiddenType::class, ['mapped' => false])
          ->add('speciality', EnumType::class, ['class' => 'App\Enum\Speciality', 'label' => 'Spécialité', 'placeholder' => 'Choisir'])
          ->add('save', SubmitType::class, ['label' => 'Ajouter', 'attr' => ['class' => 'btn-create']])
          ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $lobby = $form->getData();
            $entity_manager->persist($lobby);
            $entity_manager->flush();
            return $this->redirectToRoute('lobby_list');
        }
        
        $join->handleRequest($request);
        if ($join->isSubmitted() && $join->isValid()) {
            $lobby = $join->getData();
            $entity_manager->persist($lobby);
            $entity_manager->flush();
            return $this->redirectToRoute('lobby_list');
        }

        return $this->render('lobby/index.html.twig', [
            'controller_name' => 'LobbyController',
            'form' => $form,
            'join' => $join,
            'lobbies' => $lobbies
        ]);
    }
}
