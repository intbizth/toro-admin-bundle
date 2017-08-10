<?php

namespace Toro\Bundle\AdminBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\TranslatableTrait;

/**
 * @method MenuTranslation getTranslation($local = null)
 */
class Menu implements MenuInterface
{
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var MenuInterface
     */
    protected $root;

    /**
     * @var MenuInterface
     */
    protected $parent;

    /**
     * @var Collection|MenuInterface[]
     */
    protected $children;

    /**
     * @var int
     */
    protected $left;

    /**
     * @var int
     */
    protected $right;

    /**
     * @var int
     */
    protected $level;

    /**
     * @var array
     */
    private $options = array();

    /**
     * @var bool
     */
    private $display = true;

    /**
     * @var bool
     */
    private $displayChildren = true;

    public function __construct()
    {
        $this->initializeTranslationsCollection();

        $this->children = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->getTranslation()->__toString();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * {@inheritdoc}
     */
    public function isRoot()
    {
        return null === $this->parent;
    }

    /**
     * @return MenuInterface
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(MenuInterface $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getParents()
    {
        if (null === $parent = $this->getParent()) {
            return [];
        }

        $parents = [$parent];

        while (null !== $parent->getParent()) {
            $parents[] = $parent = $parent->getParent();
        }

        return $parents;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChild(MenuInterface $menu)
    {
        return $this->children->contains($menu);
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(MenuInterface $menu)
    {
        if (!$this->hasChild($menu)) {
            $menu->setParent($this);

            $this->children->add($menu);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(MenuInterface $menu)
    {
        if ($this->hasChild($menu)) {
            $menu->setParent(null);

            $this->children->removeElement($menu);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getTranslation()->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->getTranslation()->setName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getPermalink()
    {
        return $this->getTranslation()->getPermalink();
    }

    /**
     * {@inheritdoc}
     */
    public function setPermalink($permalink)
    {
        $this->getTranslation()->setPermalink($permalink);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->getTranslation()->getDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->getTranslation()->setDescription($description);
    }

    /**
     * {@inheritdoc}
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * {@inheritdoc}
     */
    public function setLeft($left)
    {
        $this->left = $left;
    }

    /**
     * {@inheritdoc}
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * {@inheritdoc}
     */
    public function setRight($right)
    {
        $this->right = $right;
    }

    /**
     * {@inheritdoc}
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * {@inheritdoc}
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function isDisplay()
    {
        return $this->display;
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplay($display)
    {
        $this->display = $display;
    }

    /**
     * {@inheritdoc}
     */
    public function isDisplayChildren()
    {
        return $this->displayChildren;
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayChildren($displayChildren)
    {
        $this->displayChildren = $displayChildren;
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new MenuTranslation();
    }
}
