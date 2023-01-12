<?php

namespace App\Manager\Application\Command\Register\Presenter;

use App\Manager\Application\Command\Register\Response\RegisterWorkerNodeResponse;
use App\Shared\Application\ResponsePresenterInterface;

abstract class RegisterWorkerNodePresenter implements ResponsePresenterInterface
{
    abstract public function present(RegisterWorkerNodeResponse $registerResponse): void;
}
