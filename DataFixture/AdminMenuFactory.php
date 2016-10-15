<?php

namespace Toro\Bundle\AdminBundle\DataFixture;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Toro\Bundle\AdminBundle\Doctrine\ORM\MenuRepositoryInterface;
use Toro\Bundle\AdminBundle\Model\MenuInterface;
use Toro\Bundle\FixtureBundle\DataFixture\Factory\AbstractLocaleAwareFactory;
use Toro\Bundle\FixtureBundle\StringInflector;

final class AdminMenuFactory extends AbstractLocaleAwareFactory
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var MenuRepositoryInterface
     */
    private $repository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface $factory
     * @param MenuRepositoryInterface $repository
     */
    public function __construct(
        FactoryInterface $factory,
        MenuRepositoryInterface $repository
    ) {
        $this->factory = $factory;
        $this->repository = $repository;
        $this->faker = \Faker\Factory::create();

        $this->optionsResolver =
            (new OptionsResolver())
                ->setDefault('name', function (Options $options) {
                    return $this->faker->words(3, true);
                })
                ->setDefault('code', function (Options $options) {
                    return StringInflector::nameToCode($options['name']);
                })
                ->setDefault('description', function (Options $options) {
                    return $this->faker->paragraph;
                })
                ->setDefault('children', [])
                ->setAllowedTypes('children', ['array'])

                ->setDefault('options', [])
                ->setAllowedTypes('options', ['array'])

                ->setDefault('display', true)
                ->setAllowedTypes('display', ['boolean'])

                ->setDefault('display_children', true)
                ->setAllowedTypes('display_children', ['boolean'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create($key, array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var MenuInterface $menu */
        $menu = $this->repository->findOneBy(['code' => $options['code']]);

        if (null === $menu) {
            $menu = $this->factory->createNew();
        }

        $menu->setCode($options['code']);
        $menu->setOptions($options['options']);
        $menu->setDisplay($options['display']);
        $menu->setDisplayChildren($options['display_children']);

        $this->setLocalizedData($menu, ['name', 'description'], $options);

        foreach ($options['children'] as $key => $childOptions) {
            $menu->addChild($this->create($key, $childOptions));
        }

        return $menu;
    }
}
