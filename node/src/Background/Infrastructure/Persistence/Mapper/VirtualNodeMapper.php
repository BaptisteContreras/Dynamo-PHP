<?php

namespace App\Background\Infrastructure\Persistence\Mapper;

use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Background\Domain\Model\Aggregate\Ring\VirtualNode;
use App\Shared\Infrastructure\Persistence\Doctrine\VirtualNode as VirtualNodeEntity;

final class VirtualNodeMapper
{
    public static function dtoToEntity(VirtualNode $dto): VirtualNodeEntity
    {
    }

    public static function entityToDto(VirtualNodeEntity $entity, Node $node): VirtualNode
    {
        return new VirtualNode(
            $entity->getId(),
            $entity->getSubLabel(),
            $entity->getSlot(),
            $entity->getCreatedAt(),
            $node
        );
    }
}
