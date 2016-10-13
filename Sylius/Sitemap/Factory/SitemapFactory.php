<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Toro\Bundle\AdminBundle\Sylius\Sitemap\Factory;

use Toro\Bundle\AdminBundle\Sylius\Sitemap\Model\Sitemap;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
class SitemapFactory implements SitemapFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        return new Sitemap();
    }
}
