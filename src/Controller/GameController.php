<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Soldier;
use App\Entity\ZoneControl;
use App\Enum\Rank;
use App\Enum\Speciality;
use App\Enum\Zone;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/jouer', name: 'game_')]
class GameController extends AbstractController
{
    public const SKILLS = ["strength", "dexterity", "resistance", "constitution", "initiative"];
    public const STATS = ["precision", "damage", "escape", "life", "heal", "shield"];
    public const SKILLS_TRANSLATE = [
      "strength" => "Force",
      "dexterity" => "Dextérité",
      "constitution" => "Constitution",
      "resistance" => "Résistance",
      "initiative" => "Initiative"
    ];

    public const STATS_TRANSLATE = [
        "shield" => "Défense",
        "escape" => "Esquive",
        "damage" => "Dégâts",
        "heal" => "Soin",
        "life" => "Points de vie",
        "precision" => "Précision"
    ];

    #[Route('/distribution-de-points', name: 'select')]
    public function index(PlayerRepository $player_repository, EntityManagerInterface $entity_manager): Response
    {
        $player = $player_repository->find(1);
        if ($player->getSoldiers()->count() === 0) {
            for ($i = 0; $i < 20; $i++) {
                $soldier = new Soldier();
                $player->addSoldier($soldier);
                $entity_manager->persist($soldier);
            }
            $entity_manager->persist($player);
            $entity_manager->flush();
        }

        $first_soldier = $player->getSoldiers()->first();

        $form = $this->createFormBuilder($first_soldier)
          ->add('rank', EnumType::class, ['class' => 'App\Enum\Rank', 'label' => 'Rang', 'placeholder' => 'Choisir'])
          ->add('ai', EnumType::class, ['class' => 'App\Enum\Ai', 'label' => 'Type d\'IA', 'placeholder' => 'Choisir']);
        foreach (self::SKILLS as $skill) {
            $form->add($skill, RangeType::class, [
                'label' => self::SKILLS_TRANSLATE[$skill],
                'attr' => ['min' => 0, 'max' => 10]]
            );
        }

        $form = $form->getForm();

        $rank = match ($first_soldier->getRank()) {
            Rank::NOOB => "<button><img width='30' alt='Upgrade' src='assets/images/upgrade_arrow.svg'></button>",
            Rank::ELITE => "<button><img width='30' alt='Upgrade' src='assets/images/upgrade_arrow.svg'></button><button><img width='30' alt='Downgrade' src='assets/images/downgrade_arrow.svg'></button>",
            Rank::MASTER => "<button><img width='30' alt='Downgrade' src='assets/images/downgrade_arrow.svg'></button>"
        };
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'rank' => $rank,
            'total_points' => $player->getTotalPoints(),
            'first_soldier' => $first_soldier,
            'total_points_soldier' => $first_soldier->getTotalPoints(),
            'rank_name' => $first_soldier->getRank()->name,
            'skills' => self::SKILLS,
            'stats' => self::STATS,
            'skills_translate' => self::SKILLS_TRANSLATE,
            'stats_translate' => self::STATS_TRANSLATE,
            'form' => $form,
            'soldiers' => $player->getSoldiers()
        ]);
    }

    #[Route('/creer-une-partie', name: 'create')]
    public function create(EntityManagerInterface $entity_manager): Response
    {
        $player = new Player();
        $player->setName('Player1');
        $player->setSpeciality(Speciality::ISI);

        $entity_manager->persist($player);
        $entity_manager->flush();

        return $this->redirectToRoute('game_select');
    }

    #[Route('/champs-de-bataille', name: 'battlefield')]
    public function battlefield(PlayerRepository $player_repository, EntityManagerInterface $entity_manager): Response
    {
        $player = $player_repository->find(1);
        if ($player->getTotalPoints() > 0){
            foreach ($player->getSoldiers() as $index => $soldier) {
                if (in_array($index, range(0,3))) {
                    $soldier->setRank(Rank::ELITE);
                    $soldier->setConstitution(9);
                    $soldier->setStrength(5);
                    $soldier->setInitiative(5);
                    $soldier->setDexterity(5);
                    $soldier->setResistance(5);
                    $soldier->setIsReserved(true);
                } elseif ($index === 4) {
                    $soldier->setRank(Rank::MASTER);
                    $soldier->setConstitution(14);
                    $soldier->setStrength(6);
                    $soldier->setInitiative(6);
                    $soldier->setDexterity(6);
                    $soldier->setResistance(6);
                    $soldier->setIsReserved(true);
                } else {
                    $soldier->setConstitution(4);
                    $soldier->setStrength(4);
                    $soldier->setInitiative(4);
                    $soldier->setDexterity(4);
                    $soldier->setResistance(4);
                }
                $soldier->setDamage($soldier->getStrength() * 10);
                $soldier->setLife($soldier->getConstitution() + 30);
                $soldier->setShield($soldier->getResistance() * 5);
                $soldier->setPrecision($soldier->getDexterity() * 3);
                $soldier->setEscape($soldier->getDexterity() * 3);
                $soldier->setHeal($soldier->getDexterity() * 6);
                $entity_manager->persist($soldier);
            }
        }
        $player->setTotalPoints(0);
        $entity_manager->persist($player);
        $entity_manager->flush();

        if ($player->getGame() === null) {
            $game = new Game();
            foreach (Zone::cases() as $zone) {
                $zoneControl = new ZoneControl();
                $zoneControl->setIsControlled(false);
                $zoneControl->setZone($zone);
                $zoneControl->setGame($game);
                $entity_manager->persist($zoneControl);
            }

            $game->addPlayer($player);
            $player2 = clone $player;
            $player->setGame($game);
            $entity_manager->persist($player2);
            $game->addPlayer($player2);
            $entity_manager->persist($game);
            $entity_manager->flush();
        }


        return $this->render('game/battlefield.html.twig', [
            'controller_name' => 'GameController',
            'game' => $player->getGame(),
            'soldiers' => $player->getSoldiers(),
            'nbSoldiers' => Game::MAX_SOLDIER - ( $player->getNbReservedSoldier() + $player->getGame()->getNbSoldiersPlaced())
        ]);
    }
}
