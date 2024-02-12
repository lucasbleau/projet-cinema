<?php

namespace App\DataFixtures;

use App\Entity\Film;
use App\Entity\Salle;
use App\Entity\Seance;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class FilmFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));

        for ($i=0 ; $i <= 20 ; $i++) {

            $film = new Film();
            $film->setTitreFilm($faker->movie);
            $film->setDureeFilm(random_int(30, 180));
            $manager->persist($film);

            $this->addReference("Film".$i, $film);
        }
        $manager->flush();
    }
}
