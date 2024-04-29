<?php

namespace App\Shared\Infrastructure\Persistence;

use App\Shared\Domain\Out\StorageTransactionManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineTransactionManager implements StorageTransactionManagerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
