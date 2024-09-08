<?php

namespace App\Manager\Application\Query\NodeList\ViewModel;

use Symfony\Component\HttpFoundation\Response;

class JsonBadFiltersViewModel extends JsonNodeListViewModel
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_BAD_REQUEST);
    }

    public function getError(): string
    {
        return 'incorrect filters value';
    }
}
