<?php

namespace Toro\Bundle\AdminBundle\DataFixture;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Toro\Bundle\FixtureBundle\DataFixture\AbstractResourceFixture;

final class AdminUserFixture extends AbstractResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_user';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('email')->cannotBeEmpty()->end()
                ->scalarNode('username')->cannotBeEmpty()->end()
                ->booleanNode('enabled')->end()
                ->booleanNode('api')->defaultFalse()->end()
                ->booleanNode('root')->defaultFalse()->end()
                ->variableNode('roles')->end()
                ->scalarNode('password')->cannotBeEmpty()->end()
                ->scalarNode('first_name')->cannotBeEmpty()->end()
                ->scalarNode('last_name')->cannotBeEmpty()->end()
                ->scalarNode('locale_code')->defaultValue('th_TH')->cannotBeEmpty()->end()
        ;
    }
}
