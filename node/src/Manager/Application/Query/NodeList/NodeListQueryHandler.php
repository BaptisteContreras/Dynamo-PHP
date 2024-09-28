<?php

namespace App\Manager\Application\Query\NodeList;

use App\Manager\Application\Query\NodeList\Presenter\NodeListPresenter;
use App\Manager\Application\Query\NodeList\Request\FilteredRequest;
use App\Manager\Application\Query\NodeList\Request\NodeListRequest;
use App\Manager\Application\Query\NodeList\Response\NodeListResponse;
use App\Manager\Domain\Out\Node\FinderInterface as NodeFinder;

final readonly class NodeListQueryHandler
{
    public function __construct(
        private NodeFinder $nodeFinder,
    ) {
    }

    public function __invoke(NodeListRequest $nodeListRequest, NodeListPresenter $presenter): void
    {
        if (!$nodeListRequest instanceof FilteredRequest) {
            $nodes = $this->nodeFinder->getAll();
            $presenter->present(NodeListResponse::success($nodes));

            return;
        }

        try {
            $seedFilter = $nodeListRequest->getSeed();
            $stateFilter = $nodeListRequest->getState(); // TODO add membership state filter
        } catch (\Throwable) {
            $presenter->present(NodeListResponse::errorBadFilters());

            return;
        }

        $nodes = $this->nodeFinder->searchBy($seedFilter, $stateFilter);
        $presenter->present(NodeListResponse::success($nodes));
    }
}
