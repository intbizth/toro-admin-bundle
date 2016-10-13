<?php

namespace Toro\Bundle\AdminBundle\DataCollector;

use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Toro\Bundle\AdminBundle\Application\Kernel;
use Toro\Bundle\AdminBundle\Context\KernelContextInterface;

class ToroDataCollector extends DataCollector
{
    /**
     * @var KernelContextInterface
     */
    private $kernelContext;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $channelCode;

    /**
     * @var string
     */
    private $localeCode;

    /**
     * @param KernelContextInterface $kernelContext
     */
    public function __construct(KernelContextInterface $kernelContext)
    {
        $this->kernelContext = $kernelContext;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getChannelCode()
    {
        return $this->channelCode;
    }

    /**
     * @return string
     */
    public function getLocaleCode()
    {
        return $this->localeCode;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->version = Kernel::VERSION;

        try {
            $channel = $this->kernelContext->getChannel();
            $this->channelCode = sprintf('%s - %s', $channel->getCode(), $channel->getName());
        } catch (ChannelNotFoundException $exception) {}

        try {
            $this->localeCode = $this->kernelContext->getLocaleCode();
        } catch (ChannelNotFoundException $exception) {}
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->version,
            $this->channelCode,
            $this->localeCode,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->version,
            $this->channelCode,
            $this->localeCode
            ) = unserialize($serialized);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toro';
    }
}
