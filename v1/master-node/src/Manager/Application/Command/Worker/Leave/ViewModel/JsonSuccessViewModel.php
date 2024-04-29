<?php

namespace App\Manager\Application\Command\Worker\Leave\ViewModel;

use App\Shared\Infrastructure\Http\HttpCode;

class JsonSuccessViewModel extends JsonLeaveViewModel
{
    public function __construct()
    {
        parent::__construct(HttpCode::NO_CONTENT);
    }
}
