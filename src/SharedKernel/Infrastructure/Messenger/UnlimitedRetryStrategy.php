<?php

declare(strict_types=1);

namespace SharedKernel\Infrastructure\Messenger;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Retry\RetryStrategyInterface;
use Throwable;

final class UnlimitedRetryStrategy implements RetryStrategyInterface
{
    private const DELAY_MILLISECONDS = 5000; // 5s

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function isRetryable(Envelope $message, ?Throwable $throwable = null): bool
    {
        return true;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getWaitingTime(Envelope $message, ?Throwable $throwable = null): int //phpcs:ignore
    {
        return self::DELAY_MILLISECONDS;
    }
}
