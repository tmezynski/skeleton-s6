<?php

declare(strict_types=1);

namespace SharedKernel\Application\Demo\SyncMessage;

final class DemoSyncEventListener
{
    public function __invoke(DemoSyncEvent $message): void
    {
        echo sprintf("In %s... %s = %d\n", self::class, $message->name, $message->value);
    }
}
