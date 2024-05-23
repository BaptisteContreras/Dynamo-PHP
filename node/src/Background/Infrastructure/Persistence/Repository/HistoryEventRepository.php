<?php

namespace App\Background\Infrastructure\Persistence\Repository;

use App\Background\Domain\Model\Aggregate\History\Collection\HistoryEventCollection;
use App\Background\Domain\Model\Aggregate\History\HistoryTimeline;
use App\Background\Domain\Out\History\CreatorInterface;
use App\Background\Domain\Out\History\FinderInterface;
use App\Background\Infrastructure\Persistence\Mapper\HistoryMapper;
use App\Shared\Infrastructure\Persistence\Doctrine\HistoryEvent as HistoryEventEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoryEventEntity>
 */
class HistoryEventRepository extends ServiceEntityRepository implements FinderInterface, CreatorInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoryEventEntity::class);
    }

    public function saveHistoryTimeline(HistoryTimeline $historyTimeline): void
    {
        // TODO handle existing event
        $em = $this->getEntityManager();

        foreach ($historyTimeline->getEvents() as $event) {
            $em->persist(HistoryMapper::dtoToEntity($event));
        }

        $em->flush();
    }

    public function getLocalHistoryTimeline(): HistoryTimeline
    {
        /** @var array<HistoryEventEntity> $events */
        $events = $this->findAll();

        return new HistoryTimeline(
            new HistoryEventCollection(array_map(
                fn (HistoryEventEntity $eventEntity) => HistoryMapper::entityToDto($eventEntity),
                $events
            ))
        );
    }
}
