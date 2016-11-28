<?php

namespace Toro\Bundle\AdminBundle\Form\Type;

use Sylius\Bundle\UserBundle\Form\Type\UserType as BaseUserType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminUserType extends BaseUserType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('displayName')
            ->add('localeCode', 'sylius_locale_code_choice', [
                'label' => 'toro.ui.locale',
            ])
        ;
    }
}
