<?php

namespace App\Foreground\Infrastructure\Persistence\Repository;

use App\Foreground\Domain\Model\Aggregate\Item\Item;
use App\Foreground\Domain\Out\Item\ItemUpdaterInterface;
use App\Shared\Infrastructure\Persistence\Doctrine\Item as ItemEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemEntity>
 */
class ItemRepository extends ServiceEntityRepository implements ItemUpdaterInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemEntity::class);
    }

    public function saveItem(Item $item): void
    {
        // TODO: Implement saveItem() method.
    }
}
