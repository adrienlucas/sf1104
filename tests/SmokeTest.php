<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SmokeTest extends WebTestCase
{
    public function urlAndStatusCodeProvider(): array
    {
        return [
            ['/', Response::HTTP_OK],
            ['/movie/987', Response::HTTP_OK],
            ['/movie/foobar', Response::HTTP_NOT_FOUND],
            ['/barfoo', Response::HTTP_NOT_FOUND],
        ];
    }

    /**
     * @dataProvider urlAndStatusCodeProvider
     */
    public function testUrlHasStatusCode(string $url, int $expectedStatusCode): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertSame( // stric comparison; ===
            $expectedStatusCode,
            $client->getResponse()->getStatusCode()
        );
    }
}
