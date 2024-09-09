<?php

namespace App\Manager\Application\Query\PreferenceList;

use App\Manager\Application\Query\PreferenceList\Presenter\PreferenceListPresenter;
use App\Manager\Application\Query\PreferenceList\Response\PreferenceListResponse;
use App\Manager\Domain\Out\PreferenceList\FinderInterface;

final readonly class PreferenceListQueryHandler
{
    public function __construct(
        private FinderInterface $preferenceListFinder,
    ) {
    }

    public function __invoke(PreferenceListPresenter $ringPresenter): void
    {
        $preferenceList = $this->preferenceListFinder->getPreferenceList();

        $ringPresenter->present(PreferenceListResponse::success($preferenceList));
    }
}
