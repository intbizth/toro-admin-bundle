<?php

namespace Toro\Bundle\AdminBundle\DependencyInjection\Compiler;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\DriverProvider;
use Sylius\Component\Resource\Metadata\Metadata;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProvinceTranslationResourcePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $resourceConfig = null;
        $alias = 'sylius.province_translation';
        $resources = $container->getExtensionConfig('sylius_resource');

        foreach ($resources as $resource) {
            if (isset($resource['resources']) and isset($resource['resources'][$alias])) {
                $resourceConfig = $resource['resources'][$alias];
                break;
            }
        }

        if (!$resourceConfig) {
            return;
        }

        $resources = $container->hasParameter('sylius.resources') ? $container->getParameter('sylius.resources') : [];

        $resources = array_merge($resources, [$alias => $resourceConfig]);
        $resources['sylius.province']['translation'] = $resourceConfig;

        $container->setParameter('sylius.resources', $resources);

        $metadata = Metadata::fromAliasAndConfiguration($alias, $resourceConfig);

        DriverProvider::get($metadata)->load($container, $metadata);

        $registry = $container->findDefinition('sylius.resource_registry');
        $registry->addMethodCall(
            'addFromAliasAndConfiguration',
            [
                'sylius.province',
                $container->getParameter('sylius.resources')['sylius.province'],
            ]
        );
    }
}
