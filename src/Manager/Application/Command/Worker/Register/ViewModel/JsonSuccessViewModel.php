<?php

namespace App\Manager\Application\Command\Worker\Register\ViewModel;

use App\Manager\Domain\Model\Dto\WorkerNodeDto;
use App\Shared\Infrastructure\Http\HttpCode;

class JsonSuccessViewModel extends JsonRegisterWorkerNodeViewModel
{
    public function __construct(private readonly WorkerNodeDto $workerNode)
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
