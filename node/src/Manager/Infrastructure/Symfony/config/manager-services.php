<?php

use App\Manager\Domain\Service\VirtualNode\Attribution\RingSlotSelectionStrategyInterface;
use App\Manager\Domain\Service\VirtualNode\Attribution\VirtualNodeAttributor;
use App\Shared\Domain\Event\EventHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services->instanceof(EventHandlerInterface::class)
        ->tag('messenger.message_handler', ['bus' => 'event.bus']);

    $services->instanceof(RingSlotSelectionStrategyInterface::class)
        ->tag('app.manager.ring_slot_selection_strategy');

    $services->load('App\\Manager\\Application\\', '../../../Application/**/*Handler.php');

    $services
        ->load('App\\Manager\\Domain\\', '../../../Domain')
        ->exclude(['../../../Domain/Model', '../../../Domain/Exception'])
    ;

    $services
        ->load('App\\Manager\\Infrastructure\\', '../../')
        ->exclude(['../../config', '../../Oa', '../../Persistence/**/*Mapper.php'])
    ;

    $services->set(VirtualNodeAttributor::class)
        ->arg('$ringSlotSelectionStrategies', tagged_iterator('app.manager.ring_slot_selection_strategy'));
};
