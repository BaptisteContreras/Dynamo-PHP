<?php

namespace App\Manager\Application\Command\Ring\Reset\ViewModel;

use App\Shared\Application\ViewModelWithValidationError;

class JsonValidationErrorViewModel extends JsonResetRingViewModel
{
    use ViewModelWithValidationError;
}
