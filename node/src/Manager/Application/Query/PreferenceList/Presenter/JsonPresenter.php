<?php

namespace App\Manager\Application\Query\PreferenceList\Presenter;

use App\Manager\Application\Query\PreferenceList\Response\PreferenceListResponse;
use App\Manager\Application\Query\PreferenceList\Response\SuccessResponse;
use App\Manager\Application\Query\PreferenceList\ViewModel\JsonPreferenceListViewModel;

class JsonPresenter extends PreferenceListPresenter
{
    public function present(PreferenceListResponse $ringResponse): void
    {
        match (get_class($ringResponse)) {
            SuccessResponse::class => $this->viewModel = JsonPreferenceListViewModel::success($ringResponse->getPreferenceList()),
            default => throw new \LogicException('Unexpected value'),
        };
    }
}
