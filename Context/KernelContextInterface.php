<?php

namespace Toro\Bundle\AdminBundle\Context;

use Toro\Bundle\AdminBundle\Model\ChannelInterface;

interface KernelContextInterface
{
    /**
     * @return ChannelInterface
     */
    public function getChannel();

    /**
     * @return string
     */
    public function getLocaleCode();
}
