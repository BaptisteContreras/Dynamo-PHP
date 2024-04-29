<?php

namespace App\Shared\Application;

interface ResponsePresenterInterface
{
    public function toViewModel(): ViewModelInterface;

    public function getReturnCode(): int;
}
