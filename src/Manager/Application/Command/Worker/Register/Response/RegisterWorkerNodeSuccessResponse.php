<?php

namespace App\Manager\Application\Command\Worker\Register\Response;

use App\Manager\Domain\Model\Dto\WorkerNode;

class RegisterWorkerNodeSuccessResponse extends RegisterWorkerNodeResponse
{
    public function __construct(private readonly WorkerNode $workerNode)
    {
    }

    public function getWorkerNode(): WorkerNode
    {
        return $this->workerNode;
    }
}
