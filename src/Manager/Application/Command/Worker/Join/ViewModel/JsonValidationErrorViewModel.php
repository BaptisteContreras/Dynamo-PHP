<?php

namespace App\Manager\Application\Command\Worker\Join\ViewModel;

use App\Shared\Application\ViewModelWithValidationError;

class JsonValidationErrorViewModel extends JsonJoinViewModel
{
    use ViewModelWithValidationError;
}
