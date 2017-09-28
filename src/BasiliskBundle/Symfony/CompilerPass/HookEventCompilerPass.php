<?php

namespace BasiliskBundle\Symfony\CompilerPass;

use BasiliskBundle\Hook\HookManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class HookEventCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('basilisk.hook.manager')) {
            return;
        }

        $definition = $container->findDefinition('basilisk.hook.manager');
        $taggedServices = $container->findTaggedServiceIds('basilisk.hook_listener');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addHookListener', [new Reference($id)]);
        }
    }
}
