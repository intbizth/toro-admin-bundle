<?php

namespace Toro\Bundle\AdminBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;
use Toro\Bundle\AdminBundle\Form\EventListener\BuildMenuFormSubscriber;

class MenuType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'sylius_translations', [
                'type' => 'toro_menu_translation',
                'label' => 'toro.form.menu.name',
            ])
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->addEventSubscriber(new BuildMenuFormSubscriber($builder->getFormFactory()))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toro_menu';
    }
}
