<?php

namespace App\Foreground\Infrastructure\Persistence\Repository;

use App\Foreground\Domain\Model\Aggregate\Item\Item;
use App\Foreground\Domain\Out\Item\ItemUpdaterInterface;
use App\Foreground\Infrastructure\Persistence\Mapper\ItemMapper;
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
        $em = $this->getEntityManager();

        $currentActiveItem = $this->findOneBy([
            'key' => $item->getKey(),
            'version' => $item->getVersion(),
            'active' => true,
        ]);

        try {
            $em->beginTransaction();

            if ($currentActiveItem) {
                $qb = $this->createQueryBuilder('i')
                    ->update()
                    ->set('i.active', ':activeFalse')
                    ->where('i.id = :id')
                    ->setParameter('id', $currentActiveItem->getId())
                    ->setParameter('activeFalse', false);

                $qb->getQuery()->execute();
            }

            $newItem = ItemMapper::dtoToEntity($item);

            $em->persist($newItem);
            $em->flush();
            $em->commit();
        } catch (\Throwable $e) {
            $em->rollback();
            throw $e;
        }
    }
}
