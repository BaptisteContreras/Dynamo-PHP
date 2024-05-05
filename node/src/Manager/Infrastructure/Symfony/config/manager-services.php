<?php

use App\Manager\Domain\Service\VirtualNode\Attribution\VirtualNodeAttributionStrategyInterface;
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

    $services->instanceof(VirtualNodeAttributionStrategyInterface::class)
        ->tag('app.manager.virtual_node_attribution_strategy');

    $services->load('App\\Manager\\Application\\', '../../../Application');
    $services->load('App\\Manager\\Domain\\', '../../../Domain');
    $services
        ->load('App\\Manager\\Infrastructure\\', '../../')
        ->exclude('config');

    $services->set(VirtualNodeAttributor::class)
        ->arg('$attributionStrategies', tagged_iterator('app.manager.virtual_node_attribution_strategy'));
};
