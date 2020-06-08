<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();
        $faker  =  Factory::create('fr_FR');
        $seasons= $manager->getRepository(Season::class)->findAll();

        $episodeCounter=0;
        foreach ($seasons as $season) {

            $episodeNumber=rand(0,26);
            for ($i=1; $i<=$episodeNumber; $i++) {
                $episode = new Episode();
                $episode->setSeason($season);
                $episode->setNumber($i);
                $episode->setSynopsis($faker->paragraph(3));
                $episode->setTitle($faker->text($maxNbChars = 50));
                $episode->setSlug($slugify->generate($episode->getTitle()));
                $manager->persist($episode);

                $this->addReference('episode_'.$episodeCounter, $episode);
                $episodeCounter++;
            }
        }

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
