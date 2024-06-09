<?php

namespace App\Background\Infrastructure\Persistence\Mapper;

use App\Background\Domain\Model\Aggregate\History\Event;
use App\Shared\Infrastructure\Persistence\Doctrine\HistoryEvent as HistoryEventEntity;

final class HistoryMapper
{
    public static function dtoToEntity(Event $dto): HistoryEventEntity
    {
        return new HistoryEventEntity(
            $dto->getId(),
            $dto->getNode(),
            $dto->getType(),
            $dto->getEventTime(),
            $dto->getData(),
            $dto->getSourceNode(),
            $dto->getReceivedAt()
        );
    }

    public static function entityToDto(HistoryEventEntity $entity): Event
    {
        return new Event(
            $entity->getId(),
            $entity->getNode(),
            $entity->getType(),
            $entity->getEventTime(),
            $entity->getData(),
            $entity->getSourceNode(),
            $entity->getReceivedAt()
        );
    }

    public static function mergeDtoInEntity(Event $dto, HistoryEventEntity $entity): void
    {
    }
}
