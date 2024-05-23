<?php

namespace App\Background\Infrastructure\Persistence\Mapper;

use App\Background\Domain\Model\Aggregate\History\HistoryEvent;
use App\Shared\Infrastructure\Persistence\Doctrine\HistoryEvent as HistoryEventEntity;

final class HistoryMapper
{
    public static function dtoToEntity(HistoryEvent $dto): HistoryEventEntity
    {
        return new HistoryEventEntity(
            $dto->getId(),
            $dto->getNode(),
            $dto->getType(),
            $dto->getEventTime(),
            $dto->getSourceNode(),
            $dto->getReceivedAt()
        );
    }

    public static function entityToDto(HistoryEventEntity $entity): HistoryEvent
    {
        return new HistoryEvent(
            $entity->getId(),
            $entity->getNode(),
            $entity->getType(),
            $entity->getEventTime(),
            $entity->getSourceNode(),
            $entity->getReceivedAt()
        );
    }
}
