<?php

namespace App\Foreground\Application\Command\Public\Put\Presenter;

use App\Foreground\Application\Command\Public\Put\Response\PutDataResponse;
use App\Shared\Application\ResponsePresenter;

abstract class PutDataPresenter extends ResponsePresenter
{
    abstract public function present(PutDataResponse $syncMembershipV1Response): void;

    public static function json(): self
    {
        return new JsonPutDataPresenter();
    }
}
