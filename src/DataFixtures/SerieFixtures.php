<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SerieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        for($i = 1; $i <= 50; $i++) {

            $serie = new Serie();
            $serie->setBackdrop("backdrop.png")
                ->setDateCreated($faker->dateTimeBetween('-1 year'))
                ->setName($faker->word())
                ->setGenres($faker->randomElement(["SF", "Fantasy", "Thriller", "Romance", "Dramatic", "Comedy"]))
                ->setVote($faker->numberBetween(0, 10))
                ->setFirstAirDate($faker->dateTimeBetween('-1 year'))
                ->setOverview($faker->paragraph())
                ->setPopularity($faker->numberBetween(0, 1000))
                ->setPoster('poster.png')
                ->setStatus($faker->randomElement(["returning", "ended", "canceled"]))
                ->setTmdbId($faker->randomNumber());

//            $this->addReference("serie-$i", $serie);

            $manager->persist($serie);
        }

        $manager->flush();
    }
}
