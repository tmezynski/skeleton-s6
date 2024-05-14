<?php

declare(strict_types=1);

use SharedKernel\Application\Demo\SyncMessage\DemoSyncEventListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services
        ->defaults()
        ->autowire(false)
        ->autoconfigure();

    $services
        ->set(DemoSyncEventListener::class)
        ->tag('messenger.message_handler');
};
