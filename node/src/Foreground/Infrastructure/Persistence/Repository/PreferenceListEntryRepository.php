<?php

namespace App\Foreground\Infrastructure\Persistence\Repository;

use App\Foreground\Domain\Model\Aggregate\PreferenceList\PreferenceList;
use App\Foreground\Domain\Out\PreferenceList\FinderInterface;
use App\Foreground\Infrastructure\Persistence\Mapper\PreferenceListEntryMapper;
use App\Shared\Infrastructure\Persistence\Doctrine\PreferenceListEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PreferenceListEntry>
 */
class PreferenceListEntryRepository extends ServiceEntityRepository implements FinderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PreferenceListEntry::class);
    }

    public function getPreferenceList(): PreferenceList
    {
        return new PreferenceList(PreferenceListEntryMapper::entityArrayToDtoArray($this->findAll()));
    }
}
