<?php

namespace Toro\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SecurityLoginType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, [
                'label' => 'toro.form.login.username',
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'toro.form.login.password',
            ])
            ->add('_remember_me', CheckboxType::class, [
                'label' => 'toro.form.login.remember_me',
                'required' => false,
            ])
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sylius_security_login';
    }
}
