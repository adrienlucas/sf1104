<?php

namespace App\ThirdPartyApi;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiGateway
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var string
     */
    private $apiKey;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    public function getPosterByMovieTitle(string $movieTitle): string
    {
        $apiRequest = sprintf(
            'http://www.omdbapi.com/?apikey=%s&t=%s',
            $this->apiKey,
            urlencode($movieTitle)
        );

        $apiResponse = $this->httpClient->request('GET', $apiRequest);
        return $apiResponse->toArray()['Poster'] ?? '';
//        return 'https://m.media-amazon.com/images/M/MV5BNzQzOTk3OTAtNDQ0Zi00ZTVkLWI0MTEtMDllZjNkYzNjNTc4L2ltYWdlXkEyXkFqcGdeQXVyNjU0OTQ0OTY@._V1_SX300.jpg';
    }

    public function searchMovieByTitle(string $movieTitle): array
    {
        $apiRequest = sprintf(
            'http://www.omdbapi.com/?apikey=%s&s=%s',
            $this->apiKey,
            urlencode($movieTitle)
        );

        $apiResponse = $this->httpClient->request('GET', $apiRequest);

        return $apiResponse->toArray()['Search'];
    }

    public function getMovieByImdbId(string $imdbID)
    {

        $apiRequest = sprintf(
            'http://www.omdbapi.com/?apikey=%s&i=%s',
            $this->apiKey,
            urlencode($imdbID)
        );

        $apiResponse = $this->httpClient->request('GET', $apiRequest);
        return $apiResponse->toArray();
    }
}
