<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $genre = new Genre();
        $genre->setName('Gangsta movie');
        $manager->persist($genre);
        
        $movie = new Movie();
        $movie->setTitle('The Matrix');
        $movie->setDescription('Neo learns kung-fu');
        $movie->setReleaseDate(new \DateTime('1999-03-31'));
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setTitle('The Godfather');
        $movie->setDescription('De Niro goes bruuuu');
        $movie->setReleaseDate(new \DateTime('1972-03-31'));
        $manager->persist($movie);

        $genre->addMovie($movie);

        $manager->flush();
    }
}
