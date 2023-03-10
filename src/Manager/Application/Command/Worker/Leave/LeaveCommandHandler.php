<?php

namespace App\Manager\Application\Command\Worker\Leave;

use App\Manager\Application\Command\Worker\Leave\Presenter\LeavePresenter;
use App\Manager\Application\Command\Worker\Leave\Response\LeaveResponse;
use App\Manager\Domain\Contract\Out\Finder\WorkerNodeFinder;
use App\Manager\Domain\Exception\DomainException;
use App\Manager\Domain\Service\Ring;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LeaveCommandHandler
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly Ring $ring,
        private readonly WorkerNodeFinder $workerNodeFinder
    ) {
    }

    public function __invoke(LeaveRequest $leftRequest, LeavePresenter $leftPresenter): void
    {
        $validationErrors = $this->validator->validate($leftRequest);

        if ($validationErrors->count() > 0) {
            $leftPresenter->present(LeaveResponse::withValidationError($validationErrors));

            return;
        }

        $workerNode = $this->workerNodeFinder->findOneById($leftRequest->getWorkerId());

        if (!$workerNode) {
            $leftPresenter->present(LeaveResponse::notFound());

            return;
        }

        try {
            $this->ring->leave($workerNode);

            $leftPresenter->present(LeaveResponse::success());
        } catch (DomainException $e) {
            $leftPresenter->present(LeaveResponse::withError($e));
        }
    }
}
