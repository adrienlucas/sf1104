<?php

namespace App\Command;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\ThirdPartyApi\OmdbApiGateway;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieImportCommand extends Command
{
    protected static $defaultName = 'app:movie-import';
    protected static $defaultDescription = 'Import a movie from the Omdb API.';
    /** @var OmdbApiGateway */
    private $omdbApiGateway;

    /** @var MovieRepository */
    private $movieRepository;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(OmdbApiGateway $omdbApiGateway, MovieRepository $movieRepository, ValidatorInterface $validator)
    {
        parent::__construct();
        $this->omdbApiGateway = $omdbApiGateway;
        $this->movieRepository = $movieRepository;
        $this->validator = $validator;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('movie-title', InputArgument::REQUIRED, 'The title of the movie you want to import.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $movieTitle = $input->getArgument('movie-title');

        $searchResult = $this->omdbApiGateway->searchMovieByTitle($movieTitle);
        $movieKey = $io->askQuestion(new ChoiceQuestion(
            'Please select a movie :',
            array_map(function($movie) { return $movie['Title'].'('.$movie['imdbID'].')'; }, $searchResult),
        ));

        foreach($searchResult as $res) {
            $resKey = $res['Title'].'('.$res['imdbID'].')';
            if($resKey === $movieKey) {
                $movie = $res;
            }
        }

        if(!isset($movie)){
            throw new \RuntimeException();
        }

        $movieResult = $this->omdbApiGateway->getMovieByImdbId($movie['imdbID']);

        $movie = Movie::makeFromApiResponse($movieResult);

        $violationList = $this->validator->validate($movie);

        if($violationList->count() > 0) {
            $io->error('The movie could not be imported.');
            foreach($violationList as $violation) {
                $io->error($violation->getPropertyPath() . ': ' . $violation->getMessage());
            }
            return Command::FAILURE;
        }

        $this->movieRepository->add($movie);

        $io->success(sprintf('The movie "%s" has been imported !', $movie->getTitle()));

        return Command::SUCCESS;
    }
}
