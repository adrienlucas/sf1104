<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    public function testTheMovieDetailIsDisplayed(): void
    {
        $client = static::createClient();
        $client->request('GET', '/movie/123');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div', 'Movie #123');
    }
}
