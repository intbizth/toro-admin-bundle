<?php

namespace Toro\Bundle\AdminBundle\Model;

use Toro\Bundle\CoreBundle\Model\UserInterface;

/**
 * @deprecated TODO: move this class & interface to core-bundle
 */
interface AdminUserInterface extends UserInterface
{
    const DEFAULT_ADMIN_ROLE = 'ROLE_ADMINISTRATION_ACCESS';

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName);

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @param string $lastName
     */
    public function setLastName($lastName);

    /**
     * @return string
     */
    public function getFullName();

    /**
     * @return string
     */
    public function getLocaleCode();

    /**
     * @param string $code
     */
    public function setLocaleCode($code);

    /**
     * @return string
     */
    public function getDisplayName();

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName);
}
