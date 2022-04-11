<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageControllerTest extends WebTestCase
{
    public function testHomepageIsDisplayed(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Hello World');
    }
}
