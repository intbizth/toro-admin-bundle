<?php

namespace Toro\Bundle\AdminBundle\Model;

use Sylius\Component\Addressing\Model\Province as BaseProvince;
use Sylius\Component\Resource\Model\TranslatableTrait;

/**
 * @method ProvinceTranslation getTranslation($local = null)
 */
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
    public function getAbbreviation()
    {
        return $this->getTranslation()->getAbbreviation();
    }

    /**
     * {@inheritdoc}
     */
    public function setAbbreviation($abbreviation)
    {
        $this->getTranslation()->setAbbreviation($abbreviation);
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new ProvinceTranslation();
    }
}
