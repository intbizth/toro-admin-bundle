<?php

namespace Toro\Bundle\AdminBundle\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType as BaseChannelType;
use Symfony\Component\Form\FormBuilderInterface;

class ChannelType extends BaseChannelType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('taxons', 'sylius_taxon_choice', [
                'label' => 'Taxons',
                'multiple' => true,
            ])
            ->add('locales', 'sylius_locale_choice', [
                'label' => 'Locales',
                'multiple' => true,
            ])
            ->add('defaultLocale', 'sylius_locale_choice', [
                'label' => 'Default locale',
            ])
            ->add('themeName', 'sylius_theme_name_choice', [
                'label' => 'Theme',
                'required' => false,
                'empty_data' => null,
                'empty_value' => 'Please select theme',
            ])
        ;
    }
}
