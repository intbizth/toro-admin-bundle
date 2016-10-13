<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Toro\Bundle\AdminBundle\Sylius\Sitemap\Exception;

use Toro\Bundle\AdminBundle\Sylius\Sitemap\Model\SitemapUrlInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
class SitemapUrlNotFoundException extends \Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct(SitemapUrlInterface $sitemapUrl, \Exception $previousException = null)
    {
        parent::__construct(sprintf('Sitemap url %s not found', $sitemapUrl->getLocalization()), 0, $previousException);
    }
}
