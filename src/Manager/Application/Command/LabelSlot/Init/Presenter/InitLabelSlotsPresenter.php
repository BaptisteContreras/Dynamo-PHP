<?php

namespace App\Manager\Application\Command\LabelSlot\Init\Presenter;

use App\Manager\Application\Command\LabelSlot\Init\Response\InitLabelSlotsResponse;
use App\Shared\Application\ResponsePresenter;

abstract class InitLabelSlotsPresenter extends ResponsePresenter
{
    abstract public function present(InitLabelSlotsResponse $initLabelSlotsResponse): void;

    public static function json(): self
    {
        return new JsonPresenter();
    }
}
