<?php

namespace Toro\Bundle\AdminBundle\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface MenuInterface extends CodeAwareInterface, MenuTranslationInterface, TranslatableInterface
{
    /**
     * @return bool
     */
    public function isRoot();

    /**
     * @return MenuInterface
     */
    public function getRoot();

    /**
     * @return MenuInterface
     */
    public function getParent();

    /**
     * @param null|MenuInterface $menu
     */
    public function setParent(MenuInterface $menu = null);

    /**
     * @return MenuInterface[]
     */
    public function getParents();

    /**
     * @return Collection|MenuInterface[]
     */
    public function getChildren();

    /**
     * @param MenuInterface $menu
     *
     * @return bool
     */
    public function hasChild(MenuInterface $menu);

    /**
     * @param MenuInterface $menu
     */
    public function addChild(MenuInterface $menu);

    /**
     * @param MenuInterface $menu
     */
    public function removeChild(MenuInterface $menu);

    /**
     * @return int
     */
    public function getLeft();

    /**
     * @param int $left
     */
    public function setLeft($left);

    /**
     * @return int
     */
    public function getRight();

    /**
     * @param int $right
     */
    public function setRight($right);

    /**
     * @return int
     */
    public function getLevel();

    /**
     * @param int $level
     */
    public function setLevel($level);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @return boolean
     */
    public function isDisplay();

    /**
     * @param boolean $display
     */
    public function setDisplay($display);

    /**
     * @return boolean
     */
    public function isDisplayChildren();

    /**
     * @param boolean $displayChildren
     */
    public function setDisplayChildren($displayChildren);
}
