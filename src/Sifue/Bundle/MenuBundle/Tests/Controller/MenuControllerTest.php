<?php

namespace Sifue\Bundle\MenuBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MenuControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/menu');

        $this->assertTrue($crawler->filter('html:contains("Hello")')->count() > 0);
    }
}
