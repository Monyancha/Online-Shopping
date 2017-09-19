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

namespace CoreShop\Bundle\CoreShopMembersBridgeExtension\Pimcore\Repository;

use CoreShop\Bridge\MembersBridge\Repository\RestrictableRepositoryInterface;
use CoreShop\Bridge\MembersBridge\Repository\RestrictionListingTrait;
use CoreShop\Bundle\CoreBundle\Pimcore\Repository\CategoryRepository as BaseCategoryRepository;
use CoreShop\Component\Resource\Metadata\MetadataInterface;
use MembersBundle\Security\RestrictionQuery;

class CategoryRepository extends BaseCategoryRepository implements RestrictableRepositoryInterface
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
}
