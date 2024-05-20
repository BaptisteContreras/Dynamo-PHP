<?php

namespace App\Manager\Infrastructure\Persistence\Mapper;

use App\Manager\Domain\Model\Aggregate\Node\Node;
use App\Manager\Domain\Model\Aggregate\Node\VirtualNode;
use App\Shared\Infrastructure\Persistence\Doctrine\Node as NodeEntity;
use App\Shared\Infrastructure\Persistence\Doctrine\VirtualNode as VirtualNodeEntity;

final class VirtualNodeMapper
{
    public static function dtoToEntity(VirtualNode $dto, NodeEntity $nodeEntity): VirtualNodeEntity
    {
        return new VirtualNodeEntity(
            $dto->getSubLabel(),
            $dto->getSlot(),
            $nodeEntity,
            $dto->getCreatedAt(),
            $dto->getId()
        );
    }

    public static function entityToDto(VirtualNodeEntity $entity, Node $node): VirtualNode
    {
        return new VirtualNode(
            $entity->getSubLabel(),
            $entity->getSlot(),
            $node,
            $entity->getCreatedAt(),
            $entity->getId()
        );
    }

    public static function mergeDtoInEntity(VirtualNode $dto, VirtualNodeEntity $entity): void
    {
    }
}
