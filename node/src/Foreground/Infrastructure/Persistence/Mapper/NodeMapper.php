<?php

namespace App\Foreground\Infrastructure\Persistence\Mapper;

use App\Foreground\Domain\Model\Aggregate\Node\Collection\VirtualNodeCollection;
use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Shared\Infrastructure\Persistence\Doctrine\Node as NodeEntity;
use Symfony\Component\Uid\UuidV7;

final class NodeMapper
{
    public static function entityToDto(NodeEntity $entity): Node
    {
        /** @var UuidV7 $id */
        $id = $entity->getId();

        $virtualNodeCollection = VirtualNodeCollection::createEmpty();

        $node = new Node(
            $id,
            $entity->getHost(),
            $entity->getNetworkPort(),
            $entity->getMembershipState(),
            $entity->isSelfEntry(),
            $entity->getLabel(),
            $entity->getLocalNodeState(),
            $virtualNodeCollection
        );

        foreach ($entity->getVirtualNodes() as $virtualNodeEntity) {
            $virtualNodeCollection
                ->add(VirtualNodeMapper::entityToDto($virtualNodeEntity, $node));
        }

        return $node;
    }
}
