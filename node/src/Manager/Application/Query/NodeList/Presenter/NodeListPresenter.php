<?php

namespace App\Manager\Application\Query\NodeList\Presenter;

use App\Manager\Application\Query\NodeList\Response\NodeListResponse;
use App\Shared\Application\ResponsePresenter;

abstract class NodeListPresenter extends ResponsePresenter
{
    abstract public function present(NodeListResponse $ringResponse): void;

    public static function json(): self
    {
        return new JsonPresenter();
    }
}
