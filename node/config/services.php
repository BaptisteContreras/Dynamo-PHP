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

    $services
        ->load('App\\Shared\\', '../src/Shared')
        ->exclude([
            '../src/Shared/Domain/Model',
            '../src/Shared/Domain/Const',
            '../src/Shared/Domain/Exception',
            '../src/Shared/Domain/Event/Sync',
            '../src/Shared/Domain/Event/DomainEvent.php',
            '../src/Shared/Domain/Const',
            '../src/Shared/Infrastructure/Persistence/Doctrine',
            '../src/Shared/Infrastructure/Symfony/Kernel.php',
        ])
    ;

    $services->load('DataFixtures\\', '../fixtures');

    $container->import('../src/Manager/Infrastructure/Symfony/config/manager-services.php');
    $container->import('../src/Background/Infrastructure/Symfony/config/background-services.php');
};
