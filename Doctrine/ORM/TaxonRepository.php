<?php

namespace Toro\Bundle\AdminBundle\Doctrine\ORM;

use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseTaxonRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class TaxonRepository extends BaseTaxonRepository
{
    /**
     * {@override}
     */
    public function findChildren(TaxonInterface $taxon)
    {
        $root = $taxon->isRoot() ? $taxon : $taxon->getRoot();

        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('o.root', ':root'))
            ->andWhere($queryBuilder->expr()->lt('o.right', ':right'))
            ->andWhere($queryBuilder->expr()->gt('o.left', ':left'))
            ->setParameter('root', $root)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight())
            ->orderBy('o.left', 'ASC');

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

            $taxons = array_merge($taxons, $this->findChildren($root));
        }

        return $taxons;
    }
}
