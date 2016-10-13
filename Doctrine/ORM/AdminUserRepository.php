<?php

namespace Toro\Bundle\AdminBundle\Doctrine\ORM;

use Sylius\Bundle\UserBundle\Doctrine\ORM\UserRepository;

class AdminUserRepository extends UserRepository
{
    public function createNoneRootQueryBuilder()
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder
            ->where($queryBuilder->expr()->notLike('o.roles', ':role'))
            ->setParameter('role', '%"ROLE_ROOT"%')
        ;
    }
}
