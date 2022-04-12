<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppFixtures extends Fixture
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    private function validation(object $subject)
    {
        $violationList = $this->validator->validate($subject);
        if(!empty($violationList)) {
            throw new \Exception((string) $violationList);
        }
    }

    public function load(ObjectManager $manager): void
    {
        for($i=0;$i<5;$i++) {
            $dummyMovie = new Movie();
            $dummyMovie->setTitle('Dummy '.$i);
            $dummyMovie->setDescription('Dummy description'.$i);
            $dummyMovie->setReleaseDate(new DateTime());
            $this->validation($dummyMovie);
            $manager->persist($dummyMovie);
        }

        $matrixMovie = new Movie();
        $matrixMovie->setTitle('T');
        $matrixMovie->setDescription('Neo learns kung-fu');
        $matrixMovie->setReleaseDate(new \DateTime('1999-03-31'));
        $this->validation($matrixMovie);
        $manager->persist($matrixMovie);

        $gangstaGenre = new Genre();
        $gangstaGenre->setName('Gangsta movie');
        $this->validation($gangstaGenre);
        $manager->persist($gangstaGenre);

        $godfatherMovie = new Movie();
        $godfatherMovie->setTitle('The Godfather');
        $godfatherMovie->setDescription('De Niro goes bruuuu');
        $godfatherMovie->setReleaseDate(new \DateTime('1972-03-31'));
        $godfatherMovie->setGenre($gangstaGenre);

        $this->validation($godfatherMovie);
        $manager->persist($godfatherMovie);


        $comedyGenre = new Genre();
        $comedyGenre->setName('Comedy');
        $this->validation($comedyGenre);
        $manager->persist($comedyGenre);

        $actionGenre = new Genre();
        $actionGenre->setName('Action');
        $this->validation($actionGenre);
        $manager->persist($actionGenre);

        $manager->flush();
    }
}
