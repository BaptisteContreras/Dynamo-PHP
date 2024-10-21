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

    $services->load('App\\Foreground\\Application\\', '../../../Application/**/*Handler.php');

    $services
        ->load('App\\Foreground\\Domain\\', '../../../Domain')
        ->exclude(['../../../Domain/Model', '../../../Domain/Exception'])
    ;

    $services
        ->load('App\\Foreground\\Infrastructure\\', '../../')
        ->exclude(['../../config', '../../Oa', '../../Persistence/**/*Mapper.php'])
    ;
};
