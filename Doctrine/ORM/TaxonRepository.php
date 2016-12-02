<?php

namespace Toro\Bundle\AdminBundle\Doctrine\ORM;

use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseTaxonRepository;

class TaxonRepository extends BaseTaxonRepository
{
    /**
     * {@override}
     */
    public function findChildren($parentCode)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
            ->addSelect('child')
            ->leftJoin('o.children', 'child')
            ->leftJoin('o.parent', 'parent')
            ->andWhere('parent.code = :parentCode')
            ->addOrderBy('o.left', 'ASC')
            ->setParameter('parentCode', $parentCode)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@override}
     */
    public function findNodesTreeSorted()
    {
        $taxons = [];
        $roots = $this->findRootNodes();

        foreach ($this->findRootNodes() as $root) {
            $taxons[] = $root;

            $taxons = array_merge($taxons, $this->findChildren($root->getCode()));
        }

        return $taxons;
    }
}
