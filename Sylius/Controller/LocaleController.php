<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Toro\Bundle\AdminBundle\Sylius\Controller;

use Toro\Bundle\AdminBundle\Sylius\Locale\ChannelAwareLocaleProvider;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;

/**
 * @author Aram Alipoor <aram.alipoor@gmail.com>
 */
class LocaleController extends ResourceController
{
    /**
     * @return ChannelAwareLocaleProvider
     */
    protected function getLocaleProvider()
    {
        return $this->container->get('sylius.channel_aware_locale_provider');
    }
}
