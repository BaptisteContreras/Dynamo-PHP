<?php

namespace App\Manager\Application\Query\WorkerInformations\Search\ViewModel;

use App\Shared\Application\JsonViewModelInterface;

class JsonSearchAllWorkerInformationsViewModel implements JsonViewModelInterface
{
    /**         Properties         **/
    private int $code = 200;

    /**         Constructor         **/
    public function __construct(private readonly array $searchWorkerInformationsResponses)
    {
    }

    /**         Methods         **/
    public function jsonSerialize(): mixed
    {
        return [
            'workersInformations' => $this->searchWorkerInformationsResponses,
        ];
    }

    /**         Accessor         **/
    public function getCode(): int
    {
        return $this->code;
    }
}
