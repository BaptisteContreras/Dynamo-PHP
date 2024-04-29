<?php

namespace App\Shared\Application;

abstract class ViewModel implements ViewModelInterface
{
    public function __construct(protected readonly int $code)
    {
    }

    public function getCode(): int
    {
        return $this->code;
    }
}
