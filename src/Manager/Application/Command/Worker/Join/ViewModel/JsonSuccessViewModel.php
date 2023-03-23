<?php

namespace App\Manager\Application\Command\Worker\Join\ViewModel;

use App\Manager\Domain\Model\Dto\LabelSlotDto;
use App\Manager\Domain\Model\Dto\WorkerNodeDto;
use App\Shared\Infrastructure\Http\HttpCode;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Ignore;

#[OA\Schema(
    title: 'WorkerJoinSuccessResponse',
)]
class JsonSuccessViewModel extends JsonJoinViewModel
{
    public function __construct(#[Ignore] private readonly WorkerNodeDto $workerNode)
    {
        parent::__construct(HttpCode::CREATED);
    }

    #[OA\Property(
        description: 'The unique ID of the worker in the ring',
        minimum: 1,
        example: 1
    )]
    public function getId(): int
    {
        return $this->workerNode->getId();
    }

    #[OA\Property(
        description: 'The unique label of the worker in the ring',
        example: 'A'
    )]
    public function getLabel(): string
    {
        return $this->workerNode->getLabelName();
    }

    #[OA\Property(
        type: 'array',
        items: new OA\Items(
            ref: '#/components/schemas/LabelSlot'
        )
    )]
    public function getSlots(): array
    {
        return array_map(function (LabelSlotDto $labelSlotDto) {
            return [
                'id' => $labelSlotDto->getId(),
                'position' => $labelSlotDto->getPosition(),
                'coverZoneLength' => $labelSlotDto->getCoverZoneLength(),
                'subDivision' => $labelSlotDto->getSubDivision(),
            ];
        }, $this->workerNode->getLabelSlots());
    }
}
