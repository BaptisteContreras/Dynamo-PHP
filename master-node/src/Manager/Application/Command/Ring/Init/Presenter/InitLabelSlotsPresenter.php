<?php

namespace App\Manager\Application\Command\Ring\Init\Presenter;

use App\Manager\Application\Command\Ring\Init\Response\InitLabelSlotsResponse;
use App\Shared\Application\ResponsePresenter;

abstract class InitLabelSlotsPresenter extends ResponsePresenter
{
    abstract public function present(InitLabelSlotsResponse $initLabelSlotsResponse): void;

    public static function json(): self
    {
        return new JsonPresenter();
    }
}
