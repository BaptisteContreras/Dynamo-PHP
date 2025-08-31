<?php

namespace App\Foreground\Infrastructure\Persistence\Repository;

use App\Foreground\Domain\Model\Aggregate\Item\Item;
use App\Foreground\Domain\Out\Item\ItemUpdaterInterface;
use App\Foreground\Infrastructure\Persistence\Mapper\ItemMapper;
use App\Shared\Domain\Model\Versioning\VectorClock;
use App\Shared\Infrastructure\Persistence\Doctrine\Item as ItemEntity;
use App\Shared\Infrastructure\Persistence\Type\VectorClockJson;
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
        $currentActiveItem = $this->findOneBy([
            'key' => $item->getKey(),
            'version' => $this->getStringVersionForSearch($item->getVersion()),
            'active' => true,
        ]);

        if ($currentActiveItem) {
            $currentActiveItem->disable();
        }

        $newItem = ItemMapper::dtoToEntity($item);

        $em = $this->getEntityManager();
        $em->persist($newItem);
        $em->flush();
    }

    /**
     * @see VectorClockJson about why we need to sort the vector by key
     */
    private function getStringVersionForSearch(VectorClock $vectorClock): string
    {
        $sortedVector = $vectorClock->getVector();

        ksort($sortedVector);

        return json_encode($sortedVector, JSON_THROW_ON_ERROR);
    }
}
