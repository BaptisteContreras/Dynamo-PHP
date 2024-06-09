<?php

namespace App\Background\Infrastructure\Persistence\Mapper;

use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Background\Domain\Model\Aggregate\Ring\VirtualNode;
use App\Shared\Infrastructure\Persistence\Doctrine\Node as NodeEntity;
use App\Shared\Infrastructure\Persistence\Doctrine\VirtualNode as VirtualNodeEntity;

final class VirtualNodeMapper
{
    public static function dtoToEntity(VirtualNode $dto, NodeEntity $nodeEntity): VirtualNodeEntity
    {
        return new VirtualNodeEntity(
            $dto->getLabel(),
            $dto->getSlot(),
            $nodeEntity,
            $dto->getCreatedAt(),
            $dto->isActive(),
            $dto->getId()
        );
    }

    public static function entityToDto(VirtualNodeEntity $entity, Node $node): VirtualNode
    {
        return new VirtualNode(
            $entity->getId(),
            $entity->getSubLabel(),
            $entity->getSlot(),
            $entity->getCreatedAt(),
            $node,
            $entity->isActive()
        );
    }

    public static function mergeDtoInEntity(VirtualNode $dto, VirtualNodeEntity $entity): void
    {
        $entity
            ->setActive($dto->isActive());
    }
}
