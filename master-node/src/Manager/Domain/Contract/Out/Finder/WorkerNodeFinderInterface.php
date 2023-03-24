<?php

namespace App\Manager\Domain\Contract\Out\Finder;

use App\Manager\Domain\Model\Entity\WorkerNode;

interface WorkerNodeFinderInterface
{
    public function findOneById(int $id): ?WorkerNode;

    public function findOneByIpAndPort(string $ip, int $port): ?WorkerNode;

    /**
     * @return array<WorkerNode>
     */
    public function findAll(): array;
}
