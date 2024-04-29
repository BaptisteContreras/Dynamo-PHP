<?php

namespace App\Manager\Application\Command\Join\Presenter;

use App\Manager\Application\Command\Join\Response\JoinResponse;
use App\Shared\Application\ResponsePresenter;

abstract class JoinPresenter extends ResponsePresenter
{
    abstract public function present(JoinResponse $joinResponse): void;

    public static function json(): self
    {
        return new JsonPresenter();
    }
}
