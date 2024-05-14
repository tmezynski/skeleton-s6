<?php

declare(strict_types=1);

namespace SharedKernel\Application\Message;

use SharedKernel\Domain\Uuid;

final readonly class TraceableStampFactory
{
    public function __construct(private string $producerName)
    {
    }

    public function createFromPrevious(TraceableStamp $traceableStamp): TraceableStamp
    {
        return new TraceableStamp(
            $this->producerName,
            Uuid::generate(),
            $traceableStamp->messageId,
            $traceableStamp->correlationId,
        );
    }

    public function create(): TraceableStamp
    {
        $uuid = Uuid::generate();

        return new TraceableStamp($this->producerName, $uuid, $uuid, $uuid);
    }
}
