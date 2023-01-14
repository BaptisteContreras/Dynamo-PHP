<?php

namespace App\Manager\Application\Command\Register\ViewModel;

use App\Manager\Domain\Model\Dto\WorkerNode;
use App\Shared\Infrastructure\Http\HttpCode;

class JsonRegisterWorkerNodeSuccessViewModel extends JsonRegisterWorkerNodeViewModel
{
    public function __construct(private readonly WorkerNode $workerNode)
    {
        parent::__construct(HttpCode::CREATED);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'worker' => [
                'id' => $this->workerNode->getId(),
            ],
        ];
    }
}
