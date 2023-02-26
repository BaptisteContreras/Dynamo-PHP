<?php

namespace App\Manager\Application\Command\Worker\Register\Response;

use App\Shared\Application\ApplicationResponseWithValidationError;

class ValidationErrorResponse extends RegisterWorkerNodeResponse
{
    use ApplicationResponseWithValidationError;
}
