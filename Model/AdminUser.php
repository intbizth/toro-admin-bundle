<?php

namespace Toro\Bundle\AdminBundle\Model;

use Toro\Bundle\CoreBundle\Model\User;

/**
 * @deprecated TODO: move this class & interface to core-bundle
 */
class AdminUser extends User implements AdminUserInterface
{
    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $localeCode;

    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var array
     */
    protected $roles = [AdminUserInterface::DEFAULT_ADMIN_ROLE];

    /**
     * {@inheritdoc}
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function getFullName()
    {
        return trim(sprintf('%s %s', $this->firstName, $this->lastName));
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleCode()
    {
        return $this->localeCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocaleCode($code)
    {
        $this->localeCode = $code;
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {
        return $this->displayName ?: $this->getFullName();
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }
}
