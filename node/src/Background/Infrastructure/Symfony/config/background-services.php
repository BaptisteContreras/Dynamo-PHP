<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services->load('App\\Background\\Application\\', '../../../Application');
    $services->load('App\\Background\\Domain\\', '../../../Domain');
    $services
        ->load('App\\Background\\Infrastructure\\', '../../')
        ->exclude('config');
};
