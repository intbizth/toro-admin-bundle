<?php

namespace Toro\Bundle\AdminBundle\Sylius\Context;

use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Toro\Bundle\AdminBundle\Model\AdminUserInterface;

final class AdminBasedLocaleContext implements LocaleContextInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleCode(): string
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            throw new LocaleNotFoundException();
        }

        $adminUser = $token->getUser();

        if (!$adminUser instanceof AdminUserInterface) {
            throw new LocaleNotFoundException();
        }

        return $adminUser->getLocaleCode();
    }
}
