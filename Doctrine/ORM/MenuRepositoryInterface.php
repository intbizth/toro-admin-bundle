<?php

namespace Toro\Bundle\AdminBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Toro\Bundle\AdminBundle\Model\MenuInterface;

interface MenuRepositoryInterface
{
    /**
     * @param MenuInterface $menu
     *
     * @return MenuInterface[]
     */
    public function findChildren(MenuInterface $menu);

    /**
     * @param MenuInterface $menu
     *
     * @return MenuInterface[]
     */
    public function findChildrenAsTree(MenuInterface $menu);

    /**
     * @param string $code
     *
     * @return MenuInterface[]
     */
    public function findChildrenByRootCode($code);

    /**
     * @param string $code
     *
     * @return MenuInterface[]
     */
    public function findChildrenAsTreeByRootCode($code);

    /**
     * @return MenuInterface[]
     */
    public function findRootNodes();

    /**
     * @return MenuInterface[]
     */
    public function findNodesTreeSorted();

    /**
     * @param string $permalink
     *
     * @return MenuInterface|null
     */
    public function findOneByPermalink($permalink);

    /**
     * @param string $name
     * @param string $locale
     *
     * @return MenuInterface[]
     */
    public function findByName($name, $locale);

    /**
     * @return QueryBuilder
     */
    public function createListQueryBuilder();

    /**
     * @return QueryBuilder
     */
    public function getFormQueryBuilder();
}
