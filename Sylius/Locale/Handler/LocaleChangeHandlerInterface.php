<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Toro\Bundle\AdminBundle\Sylius\Locale\Handler;

use Toro\Bundle\AdminBundle\Sylius\Exception\HandleException;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
interface LocaleChangeHandlerInterface
{
    /**
     * @param string $code
     *
     * @throws HandleException
     */
    public function handle($code);
}
