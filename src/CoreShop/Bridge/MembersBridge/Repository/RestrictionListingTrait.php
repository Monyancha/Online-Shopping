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

namespace CoreShop\Bridge\MembersBridge\Repository;

use MembersBundle\Security\RestrictionQuery;
use Pimcore\Db\ZendCompatibility\QueryBuilder;
use Pimcore\Model\DataObject\Listing;

trait RestrictionListingTrait
{
    /**
     * @var RestrictionQuery
     */
    protected $restrictionQuery;

    /**
     * @param $restrictionQuery
     */
    public function __construct($restrictionQuery)
    {
        $this->restrictionQuery = $restrictionQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareList(Listing $list)
    {
        $restrictionQuery = $this->restrictionQuery;

        $list->onCreateQuery(function(QueryBuilder $queryBuilder) use ($restrictionQuery, $list) {
            $restrictionQuery->addRestrictionInjection($queryBuilder, $list);
        });

        return $list;
    }
}