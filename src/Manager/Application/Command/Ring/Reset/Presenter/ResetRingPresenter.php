<?php

namespace App\Manager\Application\Command\Ring\Reset\Presenter;

use App\Manager\Application\Command\Ring\Reset\Response\ResetRingResponse;
use App\Shared\Application\ResponsePresenter;

abstract class ResetRingPresenter extends ResponsePresenter
{
    abstract public function present(ResetRingResponse $resetRingResponse): void;

    public static function json(): self
    {
        return new JsonPresenter();
    }
}
