<?php

namespace App\Shared\Application;

use App\Shared\Infrastructure\Http\HttpCode;

interface ViewModelInterface
{
    public function getCode(): HttpCode;
}
