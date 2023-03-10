<?php

namespace App\Manager\Application\Command\Worker\Leave\ViewModel;

use App\Shared\Infrastructure\Http\HttpCode;

class JsonNotFoundViewModel extends JsonLeaveViewModel
{
    public function __construct()
    {
        parent::__construct(HttpCode::NOT_FOUND);
    }

    public function jsonSerialize(): mixed
    {
        return [];
    }
}
