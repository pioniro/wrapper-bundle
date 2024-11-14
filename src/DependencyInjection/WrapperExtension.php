<?php

declare(strict_types=1);

namespace Pioniro\WrapperBundle\DependencyInjection;

use Pioniro\WrapperBundle\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class WrapperExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        Loader::register($container->getParameter('kernel.cache_dir'), 'wrappers.php');
        $container->setParameter('wrappers.enabled', $mergedConfig['enabled']);
    }
}
