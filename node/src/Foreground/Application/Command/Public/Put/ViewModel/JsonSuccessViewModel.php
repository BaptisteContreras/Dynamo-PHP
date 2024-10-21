<?php

namespace App\Foreground\Application\Command\Public\Put\ViewModel;

use Symfony\Component\HttpFoundation\Response;

class JsonSuccessViewModel extends JsonPutDataViewModel
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_CREATED);
    }
}
