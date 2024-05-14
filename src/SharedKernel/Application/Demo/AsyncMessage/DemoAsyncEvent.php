<?php

declare(strict_types=1);

namespace SharedKernel\Application\Demo\AsyncMessage;

use SharedKernel\Application\Message\AbstractTraceableMessage;
use SharedKernel\Application\Message\AsyncMessageInterface;

final class DemoAsyncEvent extends AbstractTraceableMessage implements AsyncMessageInterface
{
    public function __construct(private readonly string $name, private readonly int $value)
    {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): int
    {
        return $this->value;
    }
}
