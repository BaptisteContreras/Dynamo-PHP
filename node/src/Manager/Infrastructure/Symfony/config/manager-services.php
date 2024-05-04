<?php

use App\Shared\Domain\Event\EventHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services->instanceof(EventHandlerInterface::class)
        ->tag('messenger.message_handler', ['bus' => 'event.bus']);

    $services->load('App\\Manager\\Application\\', '../../../Application');
    $services->load('App\\Manager\\Domain\\', '../../../Domain');
    $services
        ->load('App\\Manager\\Infrastructure\\', '../../')
        ->exclude('config');
};
