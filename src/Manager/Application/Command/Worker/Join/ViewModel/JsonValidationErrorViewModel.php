<?php

namespace App\Manager\Application\Command\Worker\Join\ViewModel;

use App\Shared\Application\ViewModelWithValidationError;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'WorkerJoinValidationErrorResponse'
)]
class JsonValidationErrorViewModel extends JsonJoinViewModel
{
    use ViewModelWithValidationError;
}
