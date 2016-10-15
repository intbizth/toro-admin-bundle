<?php

namespace Toro\Bundle\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Toro\Bundle\AdminBundle\Doctrine\ORM\MenuRepositoryInterface;
use Toro\Bundle\AdminBundle\Model\MenuInterface;

final class MenuBuilder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var MenuRepositoryInterface
     */
    private $repository;

    /**
     * @param FactoryInterface $factory
     * @param EventDispatcherInterface $eventDispatcher
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param MenuRepositoryInterface $repository
     * @param string $name
     */
    public function __construct(
        FactoryInterface $factory,
        EventDispatcherInterface $eventDispatcher,
        AuthorizationCheckerInterface $authorizationChecker,
        MenuRepositoryInterface $repository,
        string $name
    ) {
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
        $this->authorizationChecker = $authorizationChecker;
        $this->repository = $repository;
        $this->name = $name;
    }

    /**
     * @return ItemInterface
     */
    public function createMenu()
    {
        $root = $menu = $this->factory->createItem('root');
        $last = null;
        $level = 1;

        /** @var MenuInterface $item */
        foreach ($this->repository->findChildrenByRootCode($this->name) as $item) {
            if ($last && $item->getLevel() != $level) {
                $menu = $last;
                $level++;
            }

            $last = $menu
                ->addChild($item->getCode(), $item->getOptions())
                ->setLabel($item->getName())
            ;
        }

        $this->eventDispatcher->dispatch($this->name, new MenuBuilderEvent($this->factory, $root));

        return $root;
    }
}
