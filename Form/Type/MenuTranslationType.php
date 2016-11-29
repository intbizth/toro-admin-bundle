<?php

namespace Toro\Bundle\AdminBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MenuTranslationType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'toro.form.menu.name',
            ])
            ->add('permalink', TextType::class, [
                'required' => false,
                'label' => 'toro.form.menu.permalink',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'toro.form.menu.description',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'toro_menu_translation';
    }
}
