<?php

namespace App\Manager\Application\Command\Ring\Init\ViewModel;

use App\Shared\Application\ViewModelWithValidationError;

class JsonValidationErrorViewModel extends JsonInitLabelSlotsViewModel
{
    use ViewModelWithValidationError;
}
