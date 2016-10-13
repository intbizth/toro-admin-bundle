<?php

namespace Toro\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class SecurityLoginType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', 'text', [
                'label' => 'sylius.form.login.username',
            ])
            ->add('_password', 'password', [
                'label' => 'sylius.form.login.password',
            ])
            ->add('_remember_me', 'checkbox', [
                'label' => 'sylius.form.login.remember_me',
                'required' => false,
            ])
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_security_login';
    }
}
