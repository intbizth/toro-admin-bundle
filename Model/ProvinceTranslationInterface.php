<?php

namespace Toro\Bundle\AdminBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface ProvinceTranslationInterface extends ResourceInterface
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
    public function getAbbreviation();

    /**
     * @param string $abbreviation
     */
    public function setAbbreviation($abbreviation);
}
