<?php

namespace Toro\Bundle\AdminBundle\Context;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class KernelContext implements KernelContextInterface
{
    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @param ChannelContextInterface $channelContext
     * @param LocaleContextInterface $localeContext
     */
    public function __construct(
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext
    ) {
        $this->channelContext = $channelContext;
        $this->localeContext = $localeContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannel()
    {
        return $this->channelContext->getChannel();
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleCode()
    {
        return $this->localeContext->getLocaleCode();
    }
}
