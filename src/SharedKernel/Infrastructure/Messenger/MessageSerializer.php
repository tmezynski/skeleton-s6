<?php

declare(strict_types=1);

namespace SharedKernel\Infrastructure\Messenger;

use ReflectionClass;
use ReflectionException;
use SharedKernel\Application\Message\AbstractTraceableMessage;
use SharedKernel\Application\Message\TraceableStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Webmozart\Assert\Assert;

final readonly class MessageSerializer implements SerializerInterface
{
    private const string FORMAT = 'json';
    private const string HEADER = 'headers';
    private const string BODY = 'body';
    private const string MESSAGE_NAME = 'messageName';
    private const string CLASS_PATH = 'classPath';
    private const string TRACEABLE_STAMP = 'traceableStamp';
    private const string METADATA = 'metadata';

    public function __construct(private SymfonySerializer $serializer)
    {
    }

    /**
     * @return array<string, mixed>
     * @throws ExceptionInterface|ReflectionException
     */
    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();
        $reflection = new ReflectionClass($message::class);
        /** @var array<string, mixed> $normalizedMessage */
        $normalizedMessage = $this->serializer->normalize($envelope->getMessage());

        $header = [
            self::MESSAGE_NAME => $reflection->getShortName(),
            self::CLASS_PATH => $message::class,
        ];

        if ($message instanceof AbstractTraceableMessage) {
            $header[self::TRACEABLE_STAMP] = $normalizedMessage[self::TRACEABLE_STAMP];
            unset($normalizedMessage[self::TRACEABLE_STAMP]);
            $header[self::METADATA] = $normalizedMessage[self::METADATA];
            unset($normalizedMessage[self::METADATA]);
        }

        return [
            self::HEADER => $header,
            self::BODY => json_encode($normalizedMessage),
        ];
    }

    /** @param array<string, mixed> $encodedEnvelope */
    public function decode(array $encodedEnvelope): Envelope
    {
        /** @var array<string, mixed> $headers */
        $headers = $encodedEnvelope[self::HEADER] ?? null;
        Assert::notNull($headers, 'Header not found');

        $body = $encodedEnvelope[self::BODY] ?? null;
        Assert::stringNotEmpty($body, 'Body not found');
        Assert::inArray(self::CLASS_PATH, array_keys($headers), 'Missing classPath in header');
        Assert::inArray(self::MESSAGE_NAME, array_keys($headers), 'Missing messageName in header');
        Assert::stringNotEmpty($headers[self::CLASS_PATH], 'ClassPath can\'t be empty');
        Assert::stringNotEmpty($headers[self::MESSAGE_NAME], 'MessageName can\'t be empty');

        $className = class_exists($headers[self::CLASS_PATH])
            ? $headers[self::CLASS_PATH]
            : $headers[self::MESSAGE_NAME];

        /** @var object $message */
        $message = $this->serializer->deserialize($body, $className, self::FORMAT);

        if ($message instanceof AbstractTraceableMessage) {
            if (isset($headers[self::TRACEABLE_STAMP])) {
                /** @var TraceableStamp $traceableStamp */
                $traceableStamp = $this->serializer->denormalize(
                    $headers[self::TRACEABLE_STAMP],
                    TraceableStamp::class
                );
                $message->withStamp($traceableStamp);
            }

            /**
             * @var array<string,mixed> $metadata
             */
            $metadata = $headers[self::METADATA] ?? [];
            $message->withMetadata($metadata);
        }

        return new Envelope($message);
    }
}
