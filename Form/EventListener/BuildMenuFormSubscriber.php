<?php

namespace Toro\Bundle\AdminBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Toro\Bundle\AdminBundle\Form\Type\MenuChoiceType;
use Toro\Bundle\AdminBundle\Model\MenuInterface;

final class BuildMenuFormSubscriber implements EventSubscriberInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * @param FormFactoryInterface $factory
     */
    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $menu = $event->getData();

        if (null === $menu) {
            return;
        }

        $event
            ->getForm()
            ->add(
                $this->factory->createNamed('parent', MenuChoiceType::class, $menu->getParent(),
                    [
                        'filter' => $this->getFilterTaxonOption($menu),
                        'required' => false,
                        'label' => 'toro.form.menu.parent',
                        'placeholder' => '---',
                        'auto_initialize' => false,
                    ]
                ))
        ;
    }

    /**
     * @param MenuInterface $menu
     *
     * @return callable|null
     */
    private function getFilterTaxonOption(MenuInterface $menu)
    {
        $closure = null;

        if (null !== $menu->getId()) {
            $closure = function ($entry) use ($menu) {
                return $entry->getId() != $menu->getId();
            };
        }

        return $closure;
    }
}
