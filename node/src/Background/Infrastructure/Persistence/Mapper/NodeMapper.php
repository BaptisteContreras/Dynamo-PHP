<?php

namespace App\Background\Infrastructure\Persistence\Mapper;

use App\Background\Domain\Model\Aggregate\Ring\Collection\VirtualNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Shared\Infrastructure\Persistence\Doctrine\Node as NodeEntity;
use App\Shared\Infrastructure\Persistence\Doctrine\VirtualNode as VirtualNodeEntity;
use Symfony\Component\Uid\UuidV7;

final class NodeMapper
{
    public static function dtoToEntity(Node $dto): NodeEntity
    {
    }

    public static function entityToDto(NodeEntity $entity): Node
    {
        /** @var UuidV7 $id */
        $id = $entity->getId();
        $virtualNodeCollection = VirtualNodeCollection::createEmpty();

        $node = new Node(
            $id,
            $entity->getHost(),
            $entity->getNetworkPort(),
            $entity->getState(),
            $entity->getJoinedAt(),
            $entity->getWeight(),
            $entity->isSeed(),
            $entity->getUpdatedAt(),
            $entity->getLabel(),
            $virtualNodeCollection,
            $entity->isSelfEntry()
        );

        $virtualNodeCollection->merge(
            new VirtualNodeCollection(
                array_map(
                    fn (VirtualNodeEntity $virtualNodeEntity) => VirtualNodeMapper::entityToDto($virtualNodeEntity, $node),
                    $entity->getVirtualNodes()->toArray()
                )
            )
        );

        return $node;
    }
}
