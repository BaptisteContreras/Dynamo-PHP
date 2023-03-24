<?php

namespace App\Manager\Application\Query\Worker\Search\ViewModel;

use App\Manager\Application\Query\Worker\Search\Response\SearchWorkerInformationsResponse;
use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;
use App\Shared\Infrastructure\Http\HttpCode;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Ignore;

#[OA\Schema(
    title: 'WorkerListSuccessResponse',
)]
class JsonSearchAllWorkerInformationsViewModel extends ViewModel implements JsonViewModelInterface
{
    /**
     * @param array<SearchWorkerInformationsResponse> $searchWorkerInformationsResponses
     */
    public function __construct(#[Ignore] private readonly array $searchWorkerInformationsResponses)
    {
        parent::__construct(HttpCode::SUCCESS);
    }

    /**
     * @return array<SearchWorkerInformationsResponse>
     */
    public function getWorkers(): array
    {
        return $this->searchWorkerInformationsResponses;
    }

    #[Ignore]
    public function getCode(): HttpCode
    {
        return parent::getCode();
    }
}
