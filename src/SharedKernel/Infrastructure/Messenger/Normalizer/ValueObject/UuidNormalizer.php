<?php

declare(strict_types=1);

namespace SharedKernel\Infrastructure\Messenger\Normalizer\ValueObject;

use SharedKernel\Domain\Uuid;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class UuidNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function supportsNormalization($data, string $format = null): bool //phpcs:ignore
    {
        return $data instanceof Uuid;
    }

    /**
     * @param Uuid $object
     * @param array<string, mixed> $context
     */
    public function normalize($object, string $format = null, array $context = []): string //phpcs:ignore
    {
        return (string)$object;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool //phpcs:ignore
    {
        return Uuid::class === $type;
    }

    /**
     * @param string|null $data
     * @param array<string, mixed> $context
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): Uuid //phpcs:ignore
    {
        return Uuid::generate($data);
    }
}
