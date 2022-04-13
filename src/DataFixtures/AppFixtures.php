<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppFixtures extends Fixture
{
    private $validator;

    public function __construct(ValidatorInterface $validator, UserPasswordHasherInterface $hasher)
    {
        $this->validator = $validator;
        $this->hasher = $hasher;
    }

    private function validation(object $subject)
    {
        $violationList = $this->validator->validate($subject);
        foreach($violationList as $violation) {
            throw new \Exception((string) $violation);
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
        $matrixMovie->setTitle('The Matrix');
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

        $adrien = new User();
        $adrien->setUsername('adrien');
        $adrien->setPassword($this->hasher->hashPassword($adrien, '1234azerty'));
        $adrien->setRoles(['ROLE_ADMIN']);

        $manager->persist($adrien);

        $manager->flush();
    }
}
