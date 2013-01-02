<?php

namespace Sifue\Bundle\MenuBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MenuControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        // ログイン処理
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('ログイン')->form(array(
            '_username'  => 'admin',
            '_password'  => 'adminpass',
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('html:contains("ユーザー管理へようこそ")')->count() > 0);
    }
}
