<?php
namespace Sifue\Bundle\DomainBundle\Tests\Factory;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Sifue\Bundle\DomainBundle\Factory\UserFactory;

class UserFarctoryTest extends WebTestCase
{
    public function testUserFactory()
    {
        $client = static::createClient();
        $userFactory = static::$kernel->getContainer()->get('sifue_domain.user_factory');
        $user = $userFactory->get();

        $this->assertTrue($user->getIsActive());
        $this->assertTrue($user->getSalt() !== null);
    }
}
?>