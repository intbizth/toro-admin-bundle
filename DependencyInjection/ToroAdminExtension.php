<?php

namespace Toro\Bundle\AdminBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ToroAdminExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    /**
     * @var array
     */
    private $bundles = [
        'sylius_api',
        'sylius_channel',
        'sylius_locale',
        'sylius_mailer',
        'sylius_taxonomy',
        'sylius_user',
        'sylius_rbac',
    ];

    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration($this->getConfiguration($config, $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('toro.state_machine', $config['state_machine']);
        $this->registerResources('toro', $config['driver'], $config['resources'], $container);

        $configFiles = [
            'services.xml',
        ];

        $env = $container->getParameter('kernel.environment');

        if ('test' === $env || 'test_cached' === $env) {
            $configFiles[] = 'test_services.xml';
        }

        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $container->getExtensionConfig($this->getAlias()));

        foreach ($container->getExtensions() as $name => $extension) {
            if (in_array($name, $this->bundles)) {
                $container->prependExtensionConfig($name, ['driver' => $config['driver']]);
            }
        }

        $container->prependExtensionConfig('sylius_theme', ['context' => 'sylius.theme.context.channel_based']);

        $container->setParameter('sylius.sitemap', $config['sitemap']);
        $container->setParameter('sylius.sitemap_template', $config['sitemap']['template']);
    }
}
