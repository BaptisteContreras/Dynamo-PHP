<?php

namespace App\Manager\Application\Command\Worker\Register\ViewModel;

use App\Shared\Application\ViewModelWithValidationError;

class JsonValidationErrorViewModel extends JsonRegisterWorkerNodeViewModel
{
    use ViewModelWithValidationError;
}
