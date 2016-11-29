<?php

namespace Toro\Bundle\AdminBundle\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Sylius\Bundle\LocaleBundle\Form\Type\LocaleChoiceType;
use Sylius\Bundle\ThemeBundle\Form\Type\ThemeNameChoiceType;
use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonCodeChoiceType;

class ChannelTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('taxons', TaxonCodeChoiceType::class, [
                'label' => 'Taxons',
                'multiple' => true,
            ])
            ->add('locales', LocaleChoiceType::class, [
                'label' => 'Locales',
                'multiple' => true,
            ])
            ->add('defaultLocale', LocaleChoiceType::class, [
                'label' => 'Default locale',
                'placeholder' => null,
            ])
            ->add('themeName', ThemeNameChoiceType::class, [
                'label' => 'Theme',
                'required' => false,
                'empty_data' => null,
                'empty_value' => 'Please select theme',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ChannelType::class;
    }
}
