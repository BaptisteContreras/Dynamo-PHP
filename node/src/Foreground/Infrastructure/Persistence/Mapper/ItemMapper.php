<?php

namespace App\Foreground\Infrastructure\Persistence\Mapper;

use App\Foreground\Domain\Model\Aggregate\Item\Item;
use App\Shared\Infrastructure\Persistence\Doctrine\Item as ItemEntity;
use Symfony\Component\Uid\UuidV7;

final class ItemMapper
{
    public static function entityToDto(ItemEntity $itemEntity): Item
    {
        return Item::create(
            $itemEntity->getKey(),
            clone $itemEntity->getVersion(), // need to clone because VectorClock are not immutable, and we don't want any side effect by giving the same object as the one used in the entity
            $itemEntity->getRingKey(),
            $itemEntity->getData(),
            $itemEntity->getOwner(),
            $itemEntity->getCreatedAt()
        );
    }

    public static function dtoToEntity(Item $item): ItemEntity
    {
        return new ItemEntity(
            new UuidV7(),
            $item->getKey(),
            $item->getRingKey(),
            $item->getData(),
            $item->getVersion(), // No need to clone here, this method already returns a cloned object
            $item->getMetadata()->getCreatedAt(),
            $item->getMetadata()->getOwnerId(),
            true
        );
    }
}
