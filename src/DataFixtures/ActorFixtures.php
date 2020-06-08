<?php


namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS=[
        'Andrew Lincoln'=>['program_0','program_5'],
        'Norman Reedus'=>['program_0'],
        'Lauren Cohan'=>['program_0'],
        'Danai Gurira'=>['program_0'],
    ];

    public function load(ObjectManager $manager)
    {
        $this->loadActorsFromList($manager);
        $this->loadActorsFromFaker(50,$manager);

        $manager->flush();
    }

    private function loadActorsFromList(ObjectManager $manager)
    {
        $i=0;
        foreach (self::ACTORS as $name=>$programs) {
            $actor = new Actor();
            $actor->setName($name);
            foreach ($programs as $program) {
                $actor->addProgram($this->getReference($program));
            }

            $manager->persist($actor);

            $this->addReference('actor_'.$i, $actor);
            $i++;
        }
    }

    private function loadActorsFromFaker(int $actorNumber, ObjectManager $manager)
    {
        $faker  =  Factory::create('fr_FR');

        $programs= $manager->getRepository(Program::class)->findAll();

        for ($i=0; $i<$actorNumber; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name);
            $programNumber=rand(0,3);
            for($j=0; $j<$programNumber; $j++) {
                $actor->addProgram($programs[array_rand($programs)]);
            }

            $manager->persist($actor);

            $this->addReference('actor_faker_'.$i, $actor);
        }

    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
