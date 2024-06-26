<?php

declare(strict_types=1);

namespace Shared\Domain\Message;

abstract class TraceableMessage
{
    private ?TraceableStamp $traceableStamp = null;

    /**
     * @var array<string, mixed>
     */
    private array $metadata = [];

    public function withStamp(TraceableStamp $traceableStamp): void
    {
        $this->traceableStamp = $traceableStamp;
    }

    /**
     * @param array<string, mixed> $metadata
     */
    public function withMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function traceableStamp(): ?TraceableStamp
    {
        return $this->traceableStamp;
    }

    /**
     * @return array<string, mixed>
     */
    public function metadata(): array
    {
        return $this->metadata;
    }
}
