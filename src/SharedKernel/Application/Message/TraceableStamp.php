<?php

declare(strict_types=1);

namespace SharedKernel\Application\Message;

use SharedKernel\Domain\Uuid;

final readonly class TraceableStamp
{
    public function __construct(
        public string $producerName,
        public Uuid $messageId,
        public Uuid $causationId,
        public Uuid $correlationId
    ) {
    }
}
