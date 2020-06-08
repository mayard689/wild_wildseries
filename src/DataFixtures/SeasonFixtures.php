<?php


namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Season;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker  =  Factory::create('fr_FR');
        $programs= $manager->getRepository(Program::class)->findAll();
        $firstYear=$faker->year();

        $seasonCounter=0;
        foreach ($programs as $program) {

            $seasonNumber=rand(1,10);
            for ($i=1; $i<=$seasonNumber; $i++) {
                $season = new Season();
                $season->setProgram($program);
                $season->setNumber($i);
                $season->setDescription($faker->paragraph(3));
                $season->setYear($firstYear+$i);

                $manager->persist($season);

                $this->addReference('season_'.$seasonCounter, $season);
                $seasonCounter++;
            }

        }

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
