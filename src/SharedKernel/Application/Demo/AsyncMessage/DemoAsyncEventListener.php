<?php

declare(strict_types=1);

namespace SharedKernel\Application\Demo\AsyncMessage;

final class DemoAsyncEventListener
{
    public function __invoke(DemoAsyncEvent $event): void
    {
        echo sprintf("In %s... %s = %d\n", self::class, $event->name(), $event->value());
    }
}
