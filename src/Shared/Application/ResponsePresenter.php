<?php

namespace App\Shared\Application;

use App\Shared\Infrastructure\Http\HttpCode;

abstract class ResponsePresenter implements ResponsePresenterInterface
{
    protected ViewModelInterface $viewModel;

    public function toViewModel(): ViewModelInterface
    {
        return $this->viewModel;
    }

    public function getReturnCode(): HttpCode
    {
        return $this->viewModel->getCode();
    }
}
