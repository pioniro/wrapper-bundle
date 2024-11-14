<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\', '../src/{Command,Service,Handler}');

    $services->set('App\\SomeAnnotationHandler')
        ->tag('wrapper.handler');

    $services->set('App\\SomeCommand')
        ->tag('console.command');
};
