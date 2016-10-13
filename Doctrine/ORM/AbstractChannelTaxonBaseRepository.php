<?php
//TODO: move to the right place
namespace Toro\Bundle\AdminBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Toro\Bundle\AdminBundle\Doctrine\ORM\EntityRepository as BaseEntityRepository;

abstract class AbstractChannelTaxonBaseRepository extends BaseEntityRepository
{
    /**
     * {@inheritdoc}
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = null)
    {
        if (isset($criteria['channels'])) {
            $queryBuilder
                ->innerJoin('o.channels', 'channel')
                ->andWhere('channel = :channel')
                ->setParameter('channel', $criteria['channels'])
            ;

            unset($criteria['channels']);
        }

        if (isset($criteria['taxons'])) {

            $queryBuilder->innerJoin('o.taxons', 'taxon');

            if (is_string($criteria['taxons'])) {
                $queryBuilder->andWhere('taxon.code = :taxon');
            } else {
                $queryBuilder->andWhere('taxon = :taxon');
            }

            $queryBuilder->setParameter('taxon', $criteria['taxons']);

            unset($criteria['taxons']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }
}
