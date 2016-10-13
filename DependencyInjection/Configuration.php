<?php

namespace Toro\Bundle\AdminBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('toro_admin');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);
        $this->addSitemapSection($rootNode);
        $this->addTransitionConfig($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addResourcesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    /*->children()
                        ->arrayNode('product_variant_image')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(ProductVariantImage::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(ProductVariantImageInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()*/
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addSitemapSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('sitemap')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('template')->defaultValue('@ToroAdmin/Sitemap/show.xml.twig')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addTransitionConfig(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('state_machine')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('colors')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('negative')->defaultValue('negative')->cannotBeEmpty()->end()
                                ->scalarNode('positive')->defaultValue('positive')->cannotBeEmpty()->end()
                                ->scalarNode('na')->defaultValue('na')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                    ->children()
                        ->arrayNode('graphs')
                            ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->arrayNode('states')
                                            ->useAttributeAsKey('name')
                                            ->prototype('array')
                                                ->children()
                                                    ->scalarNode('color')->end()
                                                    ->arrayNode('translation')
                                                        ->children()
                                                            ->scalarNode('key')->end()
                                                            ->scalarNode('domain')->defaultValue('transitions')->cannotBeEmpty()->end()
                                                        ->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                        ->arrayNode('transitions')
                                            ->useAttributeAsKey('name')
                                            ->prototype('array')
                                                ->children()
                                                    ->scalarNode('color')->end()
                                                    ->arrayNode('translation')
                                                        ->children()
                                                            ->scalarNode('key')->end()
                                                            ->scalarNode('domain')->defaultValue('transitions')->cannotBeEmpty()->end()
                                                        ->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
