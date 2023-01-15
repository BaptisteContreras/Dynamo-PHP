<?php

namespace App\Manager\Application\Command\LabelSlot\Init\Response;

use App\Shared\Application\ApplicationResponseWithValidationError;

class ValidationErrorResponse extends InitLabelSlotsResponse
{
    use ApplicationResponseWithValidationError;
}
