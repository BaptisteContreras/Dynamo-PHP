<?php

namespace App\Manager\Application\Query\NodeList\Presenter;

use App\Manager\Application\Query\NodeList\Response\BadFiltersResponse;
use App\Manager\Application\Query\NodeList\Response\NodeListResponse;
use App\Manager\Application\Query\NodeList\Response\SuccessResponse;
use App\Manager\Application\Query\NodeList\ViewModel\JsonNodeListViewModel;

class JsonPresenter extends NodeListPresenter
{
    public function present(NodeListResponse $ringResponse): void
    {
        match (get_class($ringResponse)) {
            SuccessResponse::class => $this->viewModel = JsonNodeListViewModel::success($ringResponse->getNodes()),
            BadFiltersResponse::class => $this->viewModel = JsonNodeListViewModel::badFilter(),
            default => throw new \LogicException('Unexpected value')
        };
    }
}
