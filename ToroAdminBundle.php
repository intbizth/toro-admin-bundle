<?php

namespace Toro\Bundle\AdminBundle;

use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Toro\Bundle\AdminBundle\Sylius\Compiler\LazyCacheWarmupPass;
use Toro\Bundle\AdminBundle\Sylius\Compiler\SitemapProviderPass;

class ToroAdminBundle extends AbstractResourceBundle
{
    /**
     * {@inheritdoc}
     */
    public function getSupportedDrivers()
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new LazyCacheWarmupPass());
        $container->addCompilerPass(new SitemapProviderPass());
    }

    /**
     * {@inheritdoc}
     */
    protected function getModelNamespace()
    {
        return 'Toro\Bundle\AdminBundle\Model';
    }
}
