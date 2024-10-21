<?php

namespace App\Foreground\Application\Command\Public\Put;

use App\Foreground\Application\Command\Public\Put\Presenter\PutDataPresenter;
use App\Foreground\Application\Command\Public\Put\Request\PutRequest;

final readonly class PutDataCommandHandler
{
    public function __invoke(PutRequest $request, PutDataPresenter $presenter): void
    {
        dd($request);
    }
}
