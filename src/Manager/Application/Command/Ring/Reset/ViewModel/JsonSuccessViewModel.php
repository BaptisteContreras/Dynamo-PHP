<?php

namespace App\Manager\Application\Command\Ring\Reset\ViewModel;

use App\Shared\Infrastructure\Http\HttpCode;

class JsonSuccessViewModel extends JsonResetRingViewModel
{
    public function __construct()
    {
        parent::__construct(HttpCode::NO_CONTENT);
    }

    public function jsonSerialize(): mixed
    {
        return [];
    }
}
