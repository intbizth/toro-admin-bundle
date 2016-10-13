<?php

namespace Toro\Bundle\AdminBundle\Doctrine\ORM;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository as BaseEntityRepository;

class EntityRepository extends BaseEntityRepository
{
    // ใช้ clear record อื่นๆ ให้ state เป็น false เช่น ใช้กับ activeState
    public function bulkUpdate(array $paths, array $criteria = array())
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->update($this->_entityName, 'o')
        ;

        $parameters = array();

        foreach ($paths as $path => $value) {
            $queryBuilder->set($this->getPropertyName($path), ':'.$path);
            $parameters[$path] = $value;
        }

        foreach ($criteria as $key => $value) {
            $parameters[$key] = $value;
        }

        $this->applyCriteria($queryBuilder, $criteria);

        return $this->_em
            ->createQuery($queryBuilder->getDQL())
            ->execute($parameters);
    }
}
