<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Toro\Bundle\AdminBundle\Model;

use Sylius\Component\Channel\Model\ChannelInterface as BaseChannelInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Locale\Model\LocalesAwareInterface;
use Sylius\Component\Taxonomy\Model\TaxonsAwareInterface;

/**
 * Model implementing this interface should reference several:
 *   - Currencies;
 *   - Locales;
 *   - Payment methods;
 *   - Shipping methods;
 *   - Taxons.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
interface ChannelInterface extends
    BaseChannelInterface,
    LocalesAwareInterface,
    TaxonsAwareInterface
{
    /**
     * @return string
     */
    public function getThemeName();

    /**
     * @param string $themeName
     */
    public function setThemeName($themeName);

    /**
     * @param LocaleInterface $locale
     */
    public function setDefaultLocale(LocaleInterface $locale);

    /**
     * @return LocaleInterface
     */
    public function getDefaultLocale();
}
