<?php

namespace Toro\Bundle\AdminBundle\Form\Extension;

use Sylius\Bundle\AddressingBundle\Form\Type\CountryChoiceType;
use Sylius\Bundle\AddressingBundle\Form\Type\ProvinceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Component\Addressing\Model\CountryInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Intl;
use Toro\Bundle\AdminBundle\Form\Type\ProvinceTranslationType;

class ProvinceTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', CountryChoiceType::class, [
                'required' => true,
                'choice_label' => function (CountryInterface $country) {
                    return Intl::getRegionBundle()->getCountryName($country->getCode());
                },
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'Translation',
                'entry_type' => ProvinceTranslationType::class,
            ])
            ->addEventSubscriber(new AddCodeFormSubscriber())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ProvinceType::class;
    }
}
