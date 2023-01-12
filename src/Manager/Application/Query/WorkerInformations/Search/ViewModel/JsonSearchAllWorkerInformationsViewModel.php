<?php

namespace App\Manager\Application\Query\WorkerInformations\Search\ViewModel;

use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Infrastructure\Http\HttpCode;

class JsonSearchAllWorkerInformationsViewModel implements JsonViewModelInterface
{
    private HttpCode $code = HttpCode::SUCCESS;

    public function __construct(private readonly array $searchWorkerInformationsResponses)
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'workers' => $this->searchWorkerInformationsResponses,
        ];
    }

    /**         Accessor         **/
    public function getCode(): HttpCode
    {
        return $this->code;
    }
}
