<?php

namespace App\Shared\Application;

use App\Shared\Infrastructure\Http\HttpCode;

interface ResponsePresenterInterface
{
    public function toViewModel(): ViewModelInterface;

    public function getReturnCode(): HttpCode;
}
