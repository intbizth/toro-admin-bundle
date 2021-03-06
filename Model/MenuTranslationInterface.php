<?php

namespace Toro\Bundle\AdminBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface MenuTranslationInterface extends ResourceInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getPermalink();

    /**
     * @param string $permalink
     */
    public function setPermalink($permalink);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     */
    public function setDescription($description);
}
