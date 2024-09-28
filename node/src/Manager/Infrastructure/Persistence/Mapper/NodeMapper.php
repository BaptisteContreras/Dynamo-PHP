<?php

namespace App\Manager\Infrastructure\Persistence\Mapper;

use App\Manager\Domain\Model\Aggregate\Node\Collection\VirtualNodeCollection;
use App\Manager\Domain\Model\Aggregate\Node\Node;
use App\Shared\Infrastructure\Persistence\Doctrine\Node as NodeEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\UuidV7;

final class NodeMapper
{
    public static function dtoToEntity(Node $dto): NodeEntity
    {
        return new NodeEntity(
            $dto->getHost(),
            $dto->getNetworkPort(),
            $dto->getMembershipState(),
            $dto->getJoinedAt(),
            $dto->getWeight(),
            $dto->isSelfEntry(),
            $dto->isSeed(),
            $dto->getLabel(),
            $dto->getLocalNodeState(),
            new \DateTimeImmutable(),
            new ArrayCollection(),
            $dto->getId()
        );
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
            $entity->getMembershipState(),
            $entity->getJoinedAt(),
            $entity->getWeight(),
            $entity->isSelfEntry(),
            $entity->isSeed(),
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

    public static function mergeDtoInEntity(Node $dto, NodeEntity $entity): void
    {
        $entity
            ->setMembershipState($dto->getMembershipState())
            ->update()
        ;
    }
}
