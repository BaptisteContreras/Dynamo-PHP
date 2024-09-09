<?php

namespace App\Manager\Application\Query\PreferenceList\Presenter;

use App\Manager\Application\Query\PreferenceList\Response\PreferenceListResponse;
use App\Shared\Application\ResponsePresenter;

abstract class PreferenceListPresenter extends ResponsePresenter
{
    abstract public function present(PreferenceListResponse $ringResponse): void;

    public static function json(): self
    {
        return new JsonPresenter();
    }
}
