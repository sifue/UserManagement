<?php

namespace Sifue\Bundle\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // ログイン処理 (テスト用のアカウント(test1@test.com/test1を用意して下さい)
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('ログイン')->form(array(
            '_username'  => 'admin',
            '_password'  => 'adminpass',
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/user/');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $crawler = $client->click($crawler->selectLink('新規でユーザーを作成')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('作成')->form(array(
            'sifue_bundle_userbundle_usertype[username]'  => 'Test',
            'sifue_bundle_userbundle_usertype[password]'  => 'TestPassword',
            'sifue_bundle_userbundle_usertype[email]'  => 'TestEmail',
            'sifue_bundle_userbundle_usertype[is_active]'  => '1',
            'sifue_bundle_userbundle_usertype[department]'  => 'TestDepartment',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertTrue($crawler->filter('td:contains("Test")')->count() > 0);

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('編集')->link());

        $form = $crawler->selectButton('編集')->form(array(
            'sifue_bundle_userbundle_editusertype[username]'  => 'Foo',
            'sifue_bundle_userbundle_editusertype[email]'  => 'FooEmail',
            'sifue_bundle_userbundle_editusertype[is_active]'  => '1',
            'sifue_bundle_userbundle_editusertype[department]'  => 'FooDepartment',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertTrue($crawler->filter('[value="Foo"]')->count() > 0);

        // Delete the entity
        $client->submit($crawler->selectButton('削除')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }

}