<?php

namespace App\Manager\Application\Query\WorkerInformations\Search\Response;

use App\Manager\Domain\Model\Dto\WorkerNode;
use App\Shared\Application\ApplicationResponseInterface;

final class SearchWorkerInformationsResponse implements ApplicationResponseInterface
{
    /**         Constructor         **/
    public function __construct(
        private readonly int $workerInformationId,
        private readonly string $networkAddress,
        private readonly int $networkPort,
        private readonly \DateTimeImmutable $joinedAt,
        private readonly string $labelName,
        private readonly int $weight,
    ) {
    }

    /**         Methods         **/
    public static function buildFromWorkerInformations(WorkerNode $workerInformations): self
    {
        return new self(
            $workerInformations->getId(),
            $workerInformations->getNetworkAddress(),
            $workerInformations->getNetworkPort(),
            $workerInformations->getJoinedAt(),
            $workerInformations->getLabelName(),
            $workerInformations->getWeight()
        );
    }

    /**         Accessors         **/
    public function getWorkerInformationId(): int
    {
        return $this->workerInformationId;
    }

    public function getNetworkAddress(): string
    {
        return $this->networkAddress;
    }

    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function getLabelName(): string
    {
        return $this->labelName;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }
}
