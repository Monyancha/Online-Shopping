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

namespace CoreShop\Bundle\MembersBridgeBundle\DependencyInjection\Compiler;

use CoreShop\Bridge\MembersBridge\Repository\RestrictableRepositoryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RestrictableRepositoryCompiler implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // find all services extending Controller or AbstractController
        foreach ($container->getDefinitions() as $id => $definition) {
            if ($definition->isAbstract() || !$definition->getClass()) {
                continue;
            }

            $reflector = $container->getReflectionClass($definition->getClass());
            if (!$reflector) {
                continue;
            }

            if ($reflector->implementsInterface(RestrictableRepositoryInterface::class)) {
                $definition->addArgument(new Reference('members.security.restriction.query'));
            }
        }
    }
}
