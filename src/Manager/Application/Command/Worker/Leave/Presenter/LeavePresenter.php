<?php

namespace App\Manager\Application\Command\Worker\Leave\Presenter;

use App\Manager\Application\Command\Worker\Leave\Response\LeaveResponse;
use App\Shared\Application\ResponsePresenter;

abstract class LeavePresenter extends ResponsePresenter
{
    abstract public function present(LeaveResponse $leftResponse): void;

    public static function json(): self
    {
        return new JsonPresenter();
    }
}
