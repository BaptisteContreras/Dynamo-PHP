<?php

namespace App\Manager\Application\Command\Worker\Join\Response;

use App\Manager\Domain\Model\Dto\WorkerNodeDto;

class SuccessResponse extends JoinResponse
{
    public function __construct(private readonly WorkerNodeDto $workerNode)
    {
    }

    public function getWorkerNode(): WorkerNodeDto
    {
        return $this->workerNode;
    }
}
