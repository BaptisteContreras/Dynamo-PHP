<?php

namespace App\Manager\Application\Query\Ring\Presenter;

use App\Manager\Application\Query\Ring\Response\RingResponse;
use App\Shared\Application\ResponsePresenter;

abstract class RingPresenter extends ResponsePresenter
{
    abstract public function present(RingResponse $joinResponse): void;

    public static function json(): self
    {
        return new JsonPresenter();
    }
}
