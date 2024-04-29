<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services
        ->load('App\\Shared\\', '../src/Shared')
        ->exclude('../src/Shared/Infrastructure/Symfony/Kernel.php');

    $container->import('../src/Manager/Infrastructure/Symfony/config/manager-services.php');
    $container->import('../src/Background/Infrastructure/Symfony/config/background-services.php');
};
