<?php

namespace App\Manager\Application\Command\LabelSlot\Init\ViewModel;

use App\Shared\Infrastructure\Http\HttpCode;

class JsonSuccessViewModel extends JsonInitLabelSlotsViewModel
{
    public function __construct()
    {
        parent::__construct(HttpCode::CREATED);
    }

    public function jsonSerialize(): mixed
    {
        return [];
    }
}
