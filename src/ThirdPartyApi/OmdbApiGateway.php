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
        return $this->requestApi('t', $movieTitle)['Poster'] ?? '';
    }

    public function searchMovieByTitle(string $movieTitle): array
    {
        return $this->requestApi('s', $movieTitle)['Search'];
    }

    public function getMovieByImdbId(string $imdbID)
    {
        return $this->requestApi('i', $imdbID);
    }

    private function requestApi(string $parameterName, string $requestPayload)
    {

        $apiRequest = sprintf(
            'http://www.omdbapi.com/?apikey=%s&%s=%s',
            $this->apiKey,
            $parameterName,
            urlencode($requestPayload)
        );

        return $this->httpClient->request('GET', $apiRequest)->toArray();
    }
}
