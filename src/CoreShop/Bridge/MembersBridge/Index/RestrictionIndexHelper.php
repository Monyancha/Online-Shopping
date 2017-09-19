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

namespace CoreShop\Bridge\MembersBridge\Index;

use CoreShop\Component\Index\ClassHelper\ClassHelperInterface;
use CoreShop\Component\Index\Condition\Condition;
use CoreShop\Component\Index\Model\IndexableInterface;
use CoreShop\Component\Index\Model\IndexColumnInterface;
use CoreShop\Component\Index\Model\IndexInterface;
use CoreShop\Component\Index\Worker\IndexQueryHelperInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use MembersBundle\Adapter\Group\GroupInterface;
use MembersBundle\Adapter\User\UserInterface;
use MembersBundle\Configuration\Configuration;
use MembersBundle\Manager\RestrictionManager;
use Pimcore\Model\AbstractModel;

final class RestrictionIndexHelper implements ClassHelperInterface, IndexQueryHelperInterface
{
    /**
     * @var Configuration
     */
    protected $membersConfiguration;

    /**
     * @var RestrictionManager
     */
    protected $restrictionManager;

    /**
     * @param Configuration $membersConfiguration
     * @param RestrictionManager $restrictionManager
     */
    public function __construct(Configuration $membersConfiguration, RestrictionManager $restrictionManager)
    {
        $this->membersConfiguration = $membersConfiguration;
        $this->restrictionManager = $restrictionManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(IndexInterface $index)
    {
        $restrictions = $this->membersConfiguration->getConfig('restriction');

        if (!$restrictions['enabled']) {
            return false;
        }

        return in_array($index->getClass(), $restrictions['allowed_objects']);
    }

    /**
     * {@inheritdoc}
     */
    public function getSystemColumns()
    {
        return [
            'membersGroups' => IndexColumnInterface::FIELD_TYPE_STRING
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getLocalizedSystemColumns()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexColumns(IndexableInterface $indexable)
    {
        if ($indexable instanceof AbstractModel) {
            $restrictionConfiguration = $this->restrictionManager->getElementRestrictionStatus($indexable);

            return [
                'membersGroups' => ',' . implode(',', $restrictionConfiguration['restriction_groups']) . ','
            ];
        }

        return ['membersGroups' => ',,'];
    }

    /**
     * {@inheritdoc}
     */
    public function preConditionQuery(IndexInterface $index)
    {
        $allowedGroups = [];

        if ($this->restrictionManager->getUser() instanceof UserInterface) {
            $groups = $this->restrictionManager->getUser()->getGroups();
            /** @var GroupInterface $group */
            foreach($groups as $group) {
                $allowedGroups[] = $group->getId();
            }
        }

        $conditions = [];
        $mainCondition = Condition::is('members_restrictions.targetId', true);

        if (count($allowedGroups) > 0) {
            $innerConditions = [
                Condition::match('members_restrictions.ctype', 'object'),
                Condition::in('members_group_relations.groupId', $allowedGroups)
            ];

            $conditions[] = Condition::concat('', [
                $mainCondition,
                Condition::concat('', $innerConditions, 'AND')
            ], 'OR');
        }
        else {
            $conditions[] = $mainCondition;
        }

        return $conditions;
    }

    /**
     * {@inheritdoc}
     */
    public function addJoins(IndexInterface $index, QueryBuilder $queryBuilder)
    {
        $queryBuilder->leftJoin('q', 'members_restrictions', 'members_restrictions', 'members_restrictions.targetId = o_id AND members_restrictions.ctype = "object"');
        $queryBuilder->leftJoin('q', 'members_group_relations', 'members_group_relations', 'members_group_relations.restrictionId = members_restrictions.id');
    }
}