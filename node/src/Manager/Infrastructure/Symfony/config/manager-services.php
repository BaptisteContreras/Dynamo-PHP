<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services->load('App\\Manager\\Application\\', '../../../Application');
    $services->load('App\\Manager\\Domain\\', '../../../Domain');
    $services
        ->load('App\\Manager\\Infrastructure\\', '../../')
        ->exclude('config');
};
