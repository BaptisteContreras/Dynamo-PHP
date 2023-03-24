<?php

namespace App\Manager\Application\Command\Ring\Init\ViewModel;

use App\Shared\Infrastructure\Http\HttpCode;

class JsonSuccessViewModel extends JsonInitLabelSlotsViewModel
{
    public function __construct()
    {
        parent::__construct(HttpCode::NO_CONTENT);
    }
}
