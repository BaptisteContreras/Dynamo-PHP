<?php

namespace App\Background\Infrastructure\Persistence\Repository;

use App\Background\Domain\Model\Aggregate\History\Collection\HistoryEventCollection;
use App\Background\Domain\Model\Aggregate\History\HistoryEvent;
use App\Background\Domain\Model\Aggregate\History\HistoryTimeline;
use App\Background\Domain\Out\History\FinderInterface;
use App\Background\Domain\Out\History\UpdaterInterface;
use App\Background\Infrastructure\Persistence\Mapper\HistoryMapper;
use App\Shared\Infrastructure\Persistence\Doctrine\HistoryEvent as HistoryEventEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoryEventEntity>
 */
class HistoryEventRepository extends ServiceEntityRepository implements FinderInterface, UpdaterInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoryEventEntity::class);
    }

    public function saveHistoryTimeline(HistoryTimeline $historyTimeline): void
    {
        $em = $this->getEntityManager();

        foreach ($historyTimeline->getEvents() as $event) {
            $em->persist($this->createOrUpdate($event));
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

    private function createOrUpdate(HistoryEvent $event): HistoryEventEntity
    {
        $eventEntity = $this->find($event->getId());

        if ($eventEntity) {
            HistoryMapper::mergeDtoInEntity($event, $eventEntity);
        } else {
            $eventEntity = HistoryMapper::dtoToEntity($event);
        }

        return $eventEntity;
    }
}
