<?php

namespace App\Manager\Application\Command\Worker\Leave\Response;

use App\Shared\Application\ApplicationResponseWithValidationError;

class ValidationErrorResponse extends LeaveResponse
{
    use ApplicationResponseWithValidationError;
}
