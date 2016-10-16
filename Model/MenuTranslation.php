<?php

namespace Toro\Bundle\AdminBundle\Model;

use Sylius\Component\Resource\Model\AbstractTranslation;

class MenuTranslation extends AbstractTranslation implements MenuTranslationInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $permalink;

    /**
     * @var string
     */
    protected $description;

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->name;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * {@inheritdoc}
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
