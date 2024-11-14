<?php

declare(strict_types=1);

namespace Pioniro\WrapperBundle;

use Pioniro\WrapperBundle\DependencyInjection\Compiler\WrapperCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WrapperBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new WrapperCompilerPass());
    }
}
