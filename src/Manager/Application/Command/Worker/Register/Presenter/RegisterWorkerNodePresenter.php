<?php

namespace App\Manager\Application\Command\Worker\Register\Presenter;

use App\Manager\Application\Command\Worker\Register\Response\RegisterWorkerNodeResponse;
use App\Shared\Application\ResponsePresenterInterface;

abstract class RegisterWorkerNodePresenter implements ResponsePresenterInterface
{
    abstract public function present(RegisterWorkerNodeResponse $registerResponse): void;
}
