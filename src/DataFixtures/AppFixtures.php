<?php

namespace App\DataFixtures;

use App\Entity\Career;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstName('Florian');
        $user->setLastName('Berson');
        $user->setEmail('florian.berson@gmx.fr');
        $user->setRoles([]);
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$eTZCOVVwV0RpTW5aOEpSWQ$3fQeSmI10lGX1JpfAH8W012HBlU4Yw4qFeXi7ZfIL/k');
        $user->setWork('Développeur Web | Freelance');
        $user->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");
        $manager->persist($user);

        for ($i = 0; $i < 5; $i++) {
            $project = new Project();
            $project->setTitle('Projet '.$i);
            $project->setDescription('Description du projet '.$i);
            $project->setTechno('PHP | HTML | CSS');
            $project->setUser($user);
            $project->setMainImage('default.jpg');
            $manager->persist($project);
        }

        $career1 = new Career();
        $career1->setTitle('ESIEA');
        $career1->setDescription('Description du parcours');
        $career1->setStartDate(new \DateTimeImmutable('2013-09-01'));
        $career1->setEndDate(new \DateTimeImmutable('2018-06-01'));
        $career1->setUser($user);
        $manager->persist($career1);

        $career2 = new Career();
        $career2->setTitle('BAC');
        $career2->setDescription('Description du parcours');
        $career2->setStartDate(new \DateTimeImmutable('2013-07-04'));
        $career2->setUser($user);
        $manager->persist($career2);

        $manager->flush();
    }
}
