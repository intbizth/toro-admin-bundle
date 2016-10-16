<?php

namespace Toro\Bundle\AdminBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class MenuTranslationType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'toro.form.menu.name',
            ])
            ->add('permalink', 'text', [
                'required' => false,
                'label' => 'toro.form.menu.permalink',
            ])
            ->add('description', 'textarea', [
                'required' => false,
                'label' => 'toro.form.menu.description',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toro_menu_translation';
    }
}
