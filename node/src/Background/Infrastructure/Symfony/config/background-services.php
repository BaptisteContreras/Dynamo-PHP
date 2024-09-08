<?php

use App\Background\Domain\Service\PreferenceList\PreferenceListBuilder;
use App\Shared\Domain\Event\EventHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services->instanceof(EventHandlerInterface::class)
        ->tag('messenger.message_handler', ['bus' => 'event.bus']);

    $services->load('App\\Background\\Application\\', '../../../Application/**/*Handler.php');

    $services
        ->load('App\\Background\\Domain\\', '../../../Domain')
        ->exclude([
            '../../../Domain/Exception',
            '../../../Domain/Model',
        ]);

    $services
        ->load('App\\Background\\Infrastructure\\', '../../')
        ->exclude(['../../config', '../../Persistence/**/*Mapper.php']);

    $services
        ->get(PreferenceListBuilder::class)
        ->arg('$n', env('int:RING_CONFIG_N'))
    ;
};
