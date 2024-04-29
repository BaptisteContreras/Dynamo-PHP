<?php

namespace App\Shared\Application;

abstract class ResponsePresenter implements ResponsePresenterInterface
{
    protected ViewModelInterface $viewModel;

    public function toViewModel(): ViewModelInterface
    {
        return $this->viewModel;
    }

    public function getReturnCode(): int
    {
        return $this->viewModel->getCode();
    }
}
