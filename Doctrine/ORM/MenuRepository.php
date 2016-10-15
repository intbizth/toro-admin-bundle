<?php

namespace Toro\Bundle\AdminBundle\Doctrine\ORM;

use Toro\Bundle\AdminBundle\Model\MenuInterface;

class MenuRepository extends EntityRepository implements MenuRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findChildren(MenuInterface $taxon)
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
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findChildrenAsTree(MenuInterface $taxon)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
            ->addSelect('children')
            ->leftJoin('o.children', 'children')
            ->andWhere('o.parent = :parent')
            ->addOrderBy('o.root')
            ->addOrderBy('o.left')
            ->setParameter('parent', $taxon)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findChildrenByRootCode($code)
    {
        /** @var MenuInterface|null $root */
        $root = $this->findOneBy(['code' => $code]);

        if (null === $root) {
            return [];
        }

        return $this->findChildren($root);
    }

    /**
     * {@inheritdoc}
     */
    public function findChildrenAsTreeByRootCode($code)
    {
        /** @var MenuInterface|null $root */
        $root = $this->findOneBy(['code' => $code]);

        if (null === $root) {
            return [];
        }

        return $this->findChildrenAsTree($root);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByPermalink($permalink)
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
            ->where('translation.permalink = :permalink')
            ->setParameter('permalink', $permalink)
            ->orderBy('o.left')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function findByName($name, $locale)
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
            ->where('translation.name = :name')
            ->andWhere('translation.locale = :locale')
            ->setParameter('name', $name)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function findRootNodes()
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->andWhere($queryBuilder->expr()->isNull($this->getPropertyName('parent')))
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findNodesTreeSorted()
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->orderBy('o.root')
            ->addOrderBy('o.left')
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function createListQueryBuilder()
    {
        return $this->createQueryBuilder('o')->leftJoin('o.translations', 'translation');
    }

    /**
     * {@inheritdoc}
     */
    public function getFormQueryBuilder()
    {
        return $this->createQueryBuilder('o');
    }
}
