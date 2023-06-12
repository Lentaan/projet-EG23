<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Soldier;
use App\Entity\ZoneControl;
use App\Enum\Rank;
use App\Enum\Speciality;
use App\Enum\Zone;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Repository\SoldierRepository;
use App\Repository\ZoneControlRepository;
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
        $player = $player_repository->findOneBy(['name' => 'Player1']);
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

    #[Route('/abandon', name: 'fail')]
    public function fail(SoldierRepository $soldier_repository,
        ZoneControlRepository $zone_control_repository,
        PlayerRepository $player_repository,
        GameRepository $game_repository
    ): Response
    {
        $soldier_repository->removeAll();
        $zone_control_repository->removeAll();
        $player_repository->removeAll();
        $game_repository->removeAll();
        return $this->redirectToRoute('lobby_list');
    }

    #[Route('/creer-une-partie', name: 'create')]
    public function create(EntityManagerInterface $entity_manager): Response
    {
        $player = new Player();
        $player2 = new Player();
        $player->setName('Player1');
        $player2->setName('Player2');
        $player->setSpeciality(Speciality::ISI);
        $player2->setSpeciality(Speciality::GM);

        $entity_manager->persist($player);
        $entity_manager->persist($player2);
        $entity_manager->flush();

        return $this->redirectToRoute('game_select');
    }

    public function samplePoints(Player $player, $entity_manager) {
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
    }

    #[Route('/champs-de-bataille', name: 'battlefield')]
    public function battlefield(
      PlayerRepository $player_repository,
      GameRepository $game_repository,
      ZoneControlRepository $zone_control_repository,
      SoldierRepository $soldier_repository,
      EntityManagerInterface $entity_manager): Response
    {
        $player = $player_repository->findOneBy(['name' => 'Player1']);
        $player2 = $player_repository->findOneBy(['name' => 'Player2']);
        $this->samplePoints($player, $entity_manager);
        $this->samplePoints($player2, $entity_manager);
        $player->setTotalPoints(0);
        $player2->setTotalPoints(0);
        $entity_manager->persist($player);
        $entity_manager->persist($player2);
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
            $player->setGame($game);
            $player2->setGame($game);
            $entity_manager->persist($player2);
            $game->addPlayer($player2);
            $entity_manager->persist($game);
            $entity_manager->flush();
        }

        if (isset($_GET['sim']) && $_GET['sim'] === '1') {
            $zoneControls = $player->getGame()->getZoneControls();
            $zoneControl = $zoneControls->first();
            if ($zoneControl) {
                $index = 0;
                foreach ($player->getSoldiers() as $soldier) {
                    if (!$soldier->isIsReserved()){
                        if ($index % 3 === 0 && $index !== 0) {
                            $zoneControl = $zoneControls->next();
                        }
                        if ($zoneControl) {
                            $zoneControl->addSoldiersPlayer1($soldier);
                            $index++;
                        } else {
                            break;
                        }
                    } else {
                        $soldier->setIsReserved(false);
                        $entity_manager->persist($soldier);
                    }
                    $entity_manager->persist($zoneControl);
                }
                foreach ($player2->getSoldiers() as $soldier) {
                    if (!$soldier->isIsReserved()){
                        if ($index % 3 === 0 && $index !== 0) {
                            $zoneControl = $zoneControls->next();
                        }
                        if ($zoneControl) {
                            $zoneControl->addSoldiersPlayer2($soldier);
                            $index++;
                        } else {
                            break;
                        }
                    } else {
                        $soldier->setIsReserved(false);
                        $entity_manager->persist($soldier);
                    }
                    $entity_manager->persist($zoneControl);
                }
                $entity_manager->flush();
            }
        }

        if (isset($_GET['mov']) && $_GET['mov'] === '2') {
            $zoneControls = $player->getGame()->getZoneControls();
            $zoneControl = $zoneControls->first();
            if ($zoneControl) {
                $index = 0;
                foreach ($player->getSoldiers() as $soldier) {
                    if (!$soldier->isIsReserved()){
                        if ($index % 3 === 0 && $index !== 0) {
                            $zoneControl = $zoneControls->next();
                        }
                        if ($zoneControl) {
                            $zoneControl->addSoldiersPlayer1($soldier);
                            $index++;
                            if ($zoneControl->getZone()->name === 'BDE') {
                                $zoneControl->setIsControlled(true);
                                $zoneControl->setControllingPlayer($player);
                                $zoneControl->removeSoldiersPlayer1($soldier);
                                if ($index % 3 === 0){
                                    $soldier->setIsDead(true);
                                    $entity_manager->persist($soldier);
                                }
                            }
                        } else {
                            break;
                        }
                    } else {
                        $soldier->setIsReserved(false);
                        $entity_manager->persist($soldier);
                    }
                    $entity_manager->persist($zoneControl);
                }
                $entity_manager->flush();
            }

        }

        if (isset($_GET['mov']) && $_GET['mov'] === '4') {
            $zoneControls = $player->getGame()->getNoSoldierZones($player);
            $soldiers = $player->getNoZonesSoldiers();
            $soldier = $soldiers->first();
            foreach ($zoneControls as $zoneControl2) {
                $zoneControl2->addSoldiersPlayer1($soldier);
                $soldier->setZoneControl($zoneControl2);
                $soldier = $soldiers->next();
                $entity_manager->persist($zoneControl2);
                $entity_manager->persist($soldier);
            }
            $zoneControls = $player->getGame()->getUncontrolledZones($player);
            /* @var ZoneControl $zoneControl */
            $zoneControl = $zoneControls->first();
            if($zoneControl) {
                $zoneControl->setIsControlled(true);
                $zoneControl->setControllingPlayer($player->getGame()->getPlayers()->first());
                $zoneControl->removeAll();
                $entity_manager->persist($zoneControl);
                $entity_manager->flush();
            }
        }

        if (isset($_GET['mov']) && $_GET['mov'] === '6') {
            $zoneControls = $player->getGame()->getUncontrolledZones($player);
            /* @var ZoneControl $zoneControl */
            $zoneControl = $zoneControls->first();
            if($zoneControl){
                $zoneControl->setIsControlled(true);
                $zoneControl->setControllingPlayer($player->getGame()->getPlayers()->last());
                $entity_manager->persist($zoneControl);
                $entity_manager->flush();
            }
        }

        if (isset($_GET['mov']) && $_GET['mov'] === '8') {
            $zoneControls = $player->getGame()->getUncontrolledZones($player);
            /* @var ZoneControl $zoneControl */
            $zoneControl = $zoneControls->first();
            if($zoneControl){
                $zoneControl->setIsControlled(true);
                $zoneControl->setControllingPlayer($player->getGame()->getPlayers()->last());
                $entity_manager->persist($zoneControl);
                $entity_manager->flush();
            }

        }

        if (isset($_GET['mov']) && $_GET['mov'] === '10') {
            $soldier_repository->removeAll();
            $zone_control_repository->removeAll();
            $player_repository->removeAll();
            $game_repository->removeAll();
            return $this->redirectToRoute('win');
        }


        return $this->render('game/battlefield.html.twig', [
            'controller_name' => 'GameController',
            'game' => $player->getGame(),
            'soldiers' => $player->getSoldiers(),
            'nbSoldiers' => Game::MAX_SOLDIER - ( $player->getNbReservedSoldier() + $player->getGame()->getNbSoldiersPlaced() + $player->getNbDead()),
            'mov' => isset($_GET['mov']) ? $_GET['mov'] + 1 : (isset($_GET['sim']) ? $_GET['sim'] + 1 : 1),
            'is_sim' => isset($_GET['sim']),
            'player1' => $player,
            'player2' => $player2,
            'skills' => self::SKILLS,
            'stats' => self::STATS,
            'skills_translate' => self::SKILLS_TRANSLATE,
            'stats_translate' => self::STATS_TRANSLATE,
        ]);
    }
}
