<?php

namespace Toro\Bundle\AdminBundle\Form\Type;

use Sylius\Bundle\AddressingBundle\Form\Type\CountryChoiceType;
use Sylius\Bundle\AddressingBundle\Form\Type\ProvinceType as BaseProvinceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Component\Addressing\Model\CountryInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Intl;

class ProvinceType extends BaseProvinceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('country', CountryChoiceType::class, [
                'required' => true,
                'choice_label' => function (CountryInterface $country) {
                    return Intl::getRegionBundle()->getCountryName($country->getCode());
                },
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'Translation',
                'type' => ProvinceTranslationType::class,
            ])
        ;
    }
}
