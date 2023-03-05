<?php

namespace App\Manager\Application\Command\Worker\Join\Response;

use App\Shared\Application\ApplicationResponseWithValidationError;

class ValidationErrorResponse extends JoinResponse
{
    use ApplicationResponseWithValidationError;
}
