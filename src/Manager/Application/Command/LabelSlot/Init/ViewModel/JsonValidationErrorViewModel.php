<?php

namespace App\Manager\Application\Command\LabelSlot\Init\ViewModel;

use App\Shared\Application\ViewModelWithValidationError;

class JsonValidationErrorViewModel extends JsonInitLabelSlotsViewModel
{
    use ViewModelWithValidationError;
}
