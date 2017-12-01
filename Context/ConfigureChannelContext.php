<?php

namespace Toro\Bundle\AdminBundle\Context;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;

final class ConfigureChannelContext implements ChannelContextInterface
{
    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    /**
     * @var string
     */
    private $channelCode;

    /**
     * @param ChannelRepositoryInterface $channelRepository
     * @param string $channelCode
     */
    public function __construct(ChannelRepositoryInterface $channelRepository, $channelCode = null)
    {
        $this->channelRepository = $channelRepository;
        $this->channelCode = $channelCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannel(): ChannelInterface
    {
        // no config
        if (!$this->channelCode) {
            $this->assertChannelWasFound(null);
        }

        $channel = $this->channelRepository->findOneByCode($this->channelCode);

        $this->assertChannelWasFound($channel);

        return $channel;
    }

    /**
     * @param ChannelInterface|null $channel
     */
    private function assertChannelWasFound(ChannelInterface $channel = null)
    {
        if (null === $channel) {
            throw new ChannelNotFoundException();
        }
    }
}
