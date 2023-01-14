<?php

namespace App\Manager\Application\Command\Register;

use App\Manager\Application\Command\Register\Presenter\RegisterWorkerNodePresenter;
use App\Manager\Application\Command\Register\Response\RegisterWorkerNodeResponse;
use App\Manager\Domain\Exception\LockingFailsException;
use App\Manager\Domain\Exception\RingFullException;
use App\Manager\Domain\Exception\WorkerAlreadyRegisteredException;
use App\Manager\Domain\Exception\WrongWorkerStateException;
use App\Manager\Domain\Model\Dto\WorkerNode;
use App\Manager\Domain\Service\Ring;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterWorkerNodeCommandHandler
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly Ring $workerPool
    ) {
    }

    /**         Methods         **/
    public function __invoke(RegisterWorkerNodeRequest $registerRequest, RegisterWorkerNodePresenter $abstractRegisterWorkerPresenter): void
    {
        // Validate Request

        $validationErrors = $this->validator->validate($registerRequest);

        if ($validationErrors->count() > 0) {
            $abstractRegisterWorkerPresenter->present(RegisterWorkerNodeResponse::withValidationError($validationErrors));

            return;
        }

        $workerNode = $this->buildWorkerNodeFromRequest($registerRequest);

        try {
            $this->workerPool->join($workerNode);
        } catch (LockingFailsException $e) {
        } catch (RingFullException $e) {
        } catch (WorkerAlreadyRegisteredException $e) {
        } catch (WrongWorkerStateException $e) {
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
