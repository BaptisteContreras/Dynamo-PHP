<?php

namespace App\Manager\Application\Command\Worker\Register;

use App\Manager\Application\Command\Worker\Register\Presenter\RegisterWorkerNodePresenter;
use App\Manager\Application\Command\Worker\Register\Response\RegisterWorkerNodeResponse;
use App\Manager\Domain\Exception\DomainException;
use App\Manager\Domain\Model\Entity\WorkerNode;
use App\Manager\Domain\Service\Ring;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterWorkerNodeCommandHandler
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly Ring $workerPool
    ) {
    }

    public function __invoke(RegisterWorkerNodeRequest $registerRequest, RegisterWorkerNodePresenter $abstractRegisterWorkerPresenter): void
    {
        $validationErrors = $this->validator->validate($registerRequest);

        if ($validationErrors->count() > 0) {
            $abstractRegisterWorkerPresenter->present(RegisterWorkerNodeResponse::withValidationError($validationErrors));

            return;
        }

        $workerNode = $this->buildWorkerNodeFromRequest($registerRequest);

        try {
            $this->workerPool->join($workerNode);

            $abstractRegisterWorkerPresenter->present(RegisterWorkerNodeResponse::success($workerNode->readOnly()));
        } catch (DomainException $e) {
            $abstractRegisterWorkerPresenter->present(RegisterWorkerNodeResponse::withError($e));
        }
    }

    private function buildWorkerNodeFromRequest(RegisterWorkerNodeRequest $registerRequest): WorkerNode
    {
        return new WorkerNode(
            $registerRequest->getNetworkAddress(),
            $registerRequest->getNetworkPort(),
            $registerRequest->getWeight()
        );
    }
}
