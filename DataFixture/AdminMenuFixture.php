<?php

namespace Toro\Bundle\AdminBundle\DataFixture;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Toro\Bundle\FixtureBundle\DataFixture\AbstractResourceFixture;

final class AdminMenuFixture extends AbstractResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'admin_menu';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('code')->cannotBeEmpty()->end()
                ->variableNode('name')->cannotBeEmpty()->end()
                ->variableNode('description')->cannotBeEmpty()->end()
                ->variableNode('permalink')->end()
                ->variableNode('children')->end()
                ->variableNode('options')->end()
                ->variableNode('display')->defaultTrue()->end()
                ->variableNode('display_children')->defaultTrue()->end()
        ;
    }
}
