<?php

namespace Toro\Bundle\AdminBundle\Model;

use Sylius\Component\Addressing\Model\Province as BaseProvince;
use Sylius\Component\Resource\Model\TranslatableTrait;

class Province extends BaseProvince implements ProvinceInterface
{
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->translate()->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->translate()->setName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getAbbreviation()
    {
        return $this->translate()->getAbbreviation();
    }

    /**
     * {@inheritdoc}
     */
    public function setAbbreviation($abbreviation)
    {
        $this->translate()->setAbbreviation($abbreviation);
    }
}
