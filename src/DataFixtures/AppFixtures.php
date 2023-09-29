<?php

namespace App\DataFixtures;

use App\Entity\Contract;
use App\Entity\Sector;
use App\Entity\User;
use App\Repository\ContractRepository;
use App\Repository\SectorRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class AppFixtures extends Fixture
{
    private const CONTRACTS = ["CDI", "CDD", "Interim"];
    private const SECTORS = ["RH", "Informatique", "Comptabilité", "Direction"];
    private const ADMIN_EMAIL = "rh@hb.com";
    private const ADMIN_PASSWORD = "azerty123";
    private const NB_USERS = 30;

    public function __construct(
        private UserPasswordHasherInterface $hasher,
        // private ContractRepository $contractRepository, 
        // private SectorRepository $sectorRepository
        )
    {
    }

    public function load(ObjectManager $manager): void
    {
        
        // Ajout des contrats
        $contracts = []; //tableau vide qui va récupérer tous les contrats sous forme d'entité
        foreach(self::CONTRACTS as $c) {
            $contract = new Contract();
            $contract->setName($c);
            $contracts[] = $contract;
            $manager->persist($contract);
        }

        // Ajout des secteurs
        $sectors = []; // idem que contracts
        foreach(self::SECTORS as $s) {
            $sector = new Sector();
            $sector->setName($s);
            $sectors[] = $sector;
            $manager->persist($sector);
        }

        // Ajout de l'utilisateur admin
        $admin = new User();
        $admin->setEmail(self::ADMIN_EMAIL)
            ->setPassword($this->hasher->hashPassword($admin, self::ADMIN_PASSWORD))
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstname("admin")
            ->setLastname("admin")
            ->setPhoto("admin");
        $manager->persist($admin);

        // Ajout des autres utilisateurs
        $faker = Factory::create("fr_FR");

        // Je pensais au début récupérer tous les contrats et les secteurs grâce aux repo injetés dans le constructeur du loader
        // Mais ne fonctionne pas car vide au moment de l'exécution
        // les repo injectés au moment de la construction donc trop tôt je pense
        // $contracts = $this->contractRepository->findAll();
        // $sectors = $this->sectorRepository->findAll();
        
        for($i = 0; $i <= self::NB_USERS; $i++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, "test"))
                ->setRoles(["ROLE_USER"])
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPhoto($faker->imageUrl())
                ->setContract($faker->randomElement($contracts))
                ->setSector($faker->randomElement($sectors));
            
            $manager->persist($user);
        }

        $manager->flush();
    }
}
