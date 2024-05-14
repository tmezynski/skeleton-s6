<?php

declare(strict_types=1);

use SharedKernel\Application\Message\TraceableStampFactory;
use SharedKernel\UserInterface\Console\SendDemoAsyncEventCommand;
use SharedKernel\UserInterface\Console\SendDemoSyncEventCommand;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire(false)
        ->autoconfigure();

    $services
        ->set(SendDemoAsyncEventCommand::class)
        ->autowire()
        ->tag('console.command', ['command' => 'app:send-async-event'])
        ->args([service('bus'), service(TraceableStampFactory::class)]);

    $services
        ->set(SendDemoSyncEventCommand::class)
        ->autowire()
        ->tag('console.command', ['command' => 'app:send-sync-event'])
        ->args([service('bus'), service(TraceableStampFactory::class)]);
};
