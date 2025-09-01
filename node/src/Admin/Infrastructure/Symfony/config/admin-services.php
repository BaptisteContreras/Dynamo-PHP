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

    $services->load('App\\Admin\\Application\\', '../../../Application/**/*Handler.php');

    $services
        ->load('App\\Admin\\Domain\\', '../../../Domain')
        ->exclude(['../../../Domain/Model', '../../../Domain/Exception'])
    ;

    $services
        ->load('App\\Admin\\Infrastructure\\', '../../')
        ->exclude(['../../config', '../../Oa', '../../Persistence/**/*Mapper.php'])
    ;
};
