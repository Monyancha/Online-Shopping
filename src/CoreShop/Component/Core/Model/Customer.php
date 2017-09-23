<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
*/

namespace CoreShop\Component\Core\Model;

use CoreShop\Component\Address\Model\AddressesAwareTrait;
use CoreShop\Component\Customer\Model\Customer as BaseCustomer;
use CoreShop\Component\Resource\ImplementedByPimcoreException;
use MembersBundle\Adapter\User\UserTrait;

class Customer extends BaseCustomer implements CustomerInterface
{
    use AddressesAwareTrait;
    use UserTrait;

    /**
     * {@inheritdoc}
     */
    public function getCustomerGroups()
    {
        return $this->getGroups();
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerGroups($customerGroups)
    {
        return $this->setGroups($customerGroups);
    }

    /**
     * {@inheritdoc}
     */
    public function setConfirmationToken($confirmationToken)
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmationToken()
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    public function setLastLogin($time)
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    public function getLastLogin()
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    public function setGroups($groups)
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    public function setPasswordRequestedAt($date)
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    public function getPasswordRequestedAt()
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }
}