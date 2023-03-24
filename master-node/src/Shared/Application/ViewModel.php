<?php

namespace App\Shared\Application;

use App\Shared\Infrastructure\Http\HttpCode;

abstract class ViewModel implements ViewModelInterface
{
    public function __construct(protected readonly HttpCode $code)
    {
    }

    public function getCode(): HttpCode
    {
        return $this->code;
    }
}
