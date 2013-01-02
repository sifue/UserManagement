<?php

namespace Sifue\Bundle\DomainBundle\Entity;

use Sifue\Bundle\DomainBundle\Entity\User;

class UserFactory
{
    /**
     * ユーザーのインスタンスを取得する
     * @return Sifue\Bundle\DomainBundle\Entity\User
     */
    public function get()
    {
        $user = new User();
        $user->setIsActive(true);
        $user->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
        return $user;
    }
}
