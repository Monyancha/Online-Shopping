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

namespace CoreShop\Bundle\CoreBundle\Pimcore\Repository;

use CoreShop\Bundle\ProductBundle\Pimcore\Repository\ProductRepository as BaseProductRepository;
use CoreShop\Component\Core\Repository\ProductRepositoryInterface;
use CoreShop\Component\Core\Repository\RestrictableRepositoryInterface;
use CoreShop\Component\Core\Repository\RestrictionListingTrait;
use CoreShop\Component\Resource\Metadata\MetadataInterface;
use CoreShop\Component\Store\Model\StoreInterface;
use MembersBundle\Security\RestrictionQuery;

class ProductRepository extends BaseProductRepository implements ProductRepositoryInterface, RestrictableRepositoryInterface
{
    use RestrictionListingTrait {
        RestrictionListingTrait::__construct as private __restrictionConstruct;
    }

    /**
     * @param MetadataInterface $metadata
     * @param RestrictionQuery $restrictionQuery
     */
    public function __construct(MetadataInterface $metadata, RestrictionQuery $restrictionQuery)
    {
        parent::__construct($metadata);
        $this->__restrictionConstruct($restrictionQuery);
    }

    /**
     * {@inheritdoc}
     */
    public function getList()
    {
        return $this->prepareList(parent::getList());
    }

    /**
     * {@inheritdoc}
     */
    public function findLatestByStore(StoreInterface $store, $count = 8)
    {
        return $this->findBy(['enabled=1', 'stores LIKE \'%,?'.$store->getId().'?,%\''], ['o_creationDate DESC'], $count);
    }
}