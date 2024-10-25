<?php

namespace App\Foreground\Infrastructure\Persistence\Mapper;

use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Foreground\Domain\Model\Aggregate\Node\VirtualNode;
use App\Shared\Infrastructure\Persistence\Doctrine\VirtualNode as VirtualNodeEntity;

final class VirtualNodeMapper
{
    public static function entityToDto(VirtualNodeEntity $entity, Node $node): VirtualNode
    {
        return new VirtualNode(
            $entity->getSubLabel(),
            $entity->getSlot(),
            $node,
            $entity->isActive(),
            $entity->getId()
        );
    }
}
