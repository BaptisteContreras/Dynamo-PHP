<?php

namespace App\Manager\Application\Command\Worker\Leave\ViewModel;

use App\Shared\Application\ViewModelWithValidationError;

class JsonValidationErrorViewModel extends JsonLeaveViewModel
{
    use ViewModelWithValidationError;
}
