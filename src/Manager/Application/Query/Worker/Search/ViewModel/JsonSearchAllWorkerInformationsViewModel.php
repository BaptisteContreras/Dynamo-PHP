<?php

namespace App\Manager\Application\Query\Worker\Search\ViewModel;

use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;
use App\Shared\Infrastructure\Http\HttpCode;

class JsonSearchAllWorkerInformationsViewModel extends ViewModel implements JsonViewModelInterface
{
    public function __construct(private readonly array $searchWorkerInformationsResponses)
    {
        parent::__construct(HttpCode::SUCCESS);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'workers' => $this->searchWorkerInformationsResponses,
        ];
    }
}
