<?php

namespace BasiliskBundle;

use BasiliskBundle\Symfony\CompilerPass\HookEventCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BasiliskBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new HookEventCompilerPass());
    }
}
