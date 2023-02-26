<?php

namespace App\Manager\Application\Command\Worker\Register\Presenter;

use App\Manager\Application\Command\Worker\Register\Response\RegisterWorkerNodeResponse;
use App\Shared\Application\ResponsePresenter;

abstract class RegisterWorkerNodePresenter extends ResponsePresenter
{
    abstract public function present(RegisterWorkerNodeResponse $registerResponse): void;

    public static function json(): self
    {
        return new JsonPresenter();
    }
}
