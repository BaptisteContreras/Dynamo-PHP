<?php

namespace App\Manager\Application\Command\Worker\Join;

use App\Manager\Application\Command\Worker\Join\Presenter\JoinPresenter;
use App\Manager\Application\Command\Worker\Join\Response\JoinResponse;
use App\Manager\Domain\Exception\DomainException;
use App\Manager\Domain\Model\Entity\WorkerNode;
use App\Manager\Domain\Service\Ring;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JoinCommandHandler
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly Ring $workerPool
    ) {
    }

    public function __invoke(JoinRequest $registerRequest, JoinPresenter $joinWorkerNodePresenter): void
    {
        $validationErrors = $this->validator->validate($registerRequest);

        if ($validationErrors->count() > 0) {
            $joinWorkerNodePresenter->present(JoinResponse::withValidationError($validationErrors));

            return;
        }

        $workerNode = $this->buildWorkerNodeFromRequest($registerRequest);

        try {
            $this->workerPool->join($workerNode);

            $joinWorkerNodePresenter->present(JoinResponse::success($workerNode->readOnly()));
        } catch (DomainException $e) {
            $joinWorkerNodePresenter->present(JoinResponse::withError($e));
        }
    }

    private function buildWorkerNodeFromRequest(JoinRequest $registerRequest): WorkerNode
    {
        return new WorkerNode(
            $registerRequest->getNetworkAddress(),
            $registerRequest->getNetworkPort(),
            $registerRequest->getWeight()
        );
    }
}
