<?php

namespace App\Background\Infrastructure\Persistence\Repository;

use App\Background\Domain\Model\Aggregate\PreferenceList\PreferenceList;
use App\Background\Domain\Out\PreferenceList\UpdaterInterface;
use App\Background\Infrastructure\Persistence\Mapper\PreferenceListEntryMapper;
use App\Shared\Infrastructure\Persistence\Doctrine\PreferenceListEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PreferenceListEntry>
 */
class PreferenceListEntryRepository extends ServiceEntityRepository implements UpdaterInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PreferenceListEntry::class);
    }

    public function savePreferenceList(PreferenceList $preferenceList): void
    {
        $em = $this->getEntityManager();

        $em->wrapInTransaction(function (EntityManagerInterface $em) use ($preferenceList) {
            $em->createQuery(sprintf('DELETE %s', PreferenceListEntry::class))
                ->execute();

            foreach ($preferenceList->getEntries() as $dtoEntry) {
                $entityEntry = PreferenceListEntryMapper::dtoToEntity($dtoEntry);
                $em->persist($entityEntry);
            }
        });
    }
}
