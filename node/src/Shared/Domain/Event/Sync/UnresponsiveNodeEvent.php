<?php

namespace App\Shared\Domain\Event\Sync;

final class UnresponsiveNodeEvent extends SyncDomainEvent
{
    public const OPERATION_TYPE_READ = 'r';
    public const OPERATION_TYPE_WRITE = 'w';

    private function __construct(
        private string $nodeId,
        private string $operationType,
        private \DateTimeInterface $when
    ) {
    }

    public static function unresponsiveReadEvent(string $nodeId, \DateTimeInterface $when = new \DateTimeImmutable()): self
    {
        return new self($nodeId, self::OPERATION_TYPE_READ, $when);
    }

    public static function unresponsiveWriteEvent(string $nodeId, \DateTimeInterface $when = new \DateTimeImmutable()): self
    {
        return new self($nodeId, self::OPERATION_TYPE_WRITE, $when);
    }

    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    public function getWhen(): \DateTimeInterface
    {
        return $this->when;
    }

    public function isReadOperation(): bool
    {
        return self::OPERATION_TYPE_READ === $this->operationType;
    }

    public function isWriteOperation(): bool
    {
        return self::OPERATION_TYPE_WRITE === $this->operationType;
    }
}
