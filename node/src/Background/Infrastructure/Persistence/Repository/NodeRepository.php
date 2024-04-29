<?php

namespace App\Background\Infrastructure\Persistence\Repository;

use App\Shared\Infrastructure\Persistence\Doctrine\Node;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Node::class);
    }
}
