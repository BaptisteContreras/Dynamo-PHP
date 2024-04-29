<?php

namespace App\Manager\Application\Command\Worker\Join\Presenter;

use App\Manager\Application\Command\Worker\Join\Response\JoinResponse;
use App\Shared\Application\ResponsePresenter;

abstract class JoinPresenter extends ResponsePresenter
{
    abstract public function present(JoinResponse $registerResponse): void;

    public static function json(): self
    {
        return new JsonPresenter();
    }
}
