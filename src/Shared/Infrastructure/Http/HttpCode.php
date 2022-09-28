<?php

namespace App\Shared\Infrastructure\Http;

enum HttpCode: int
{
    case SUCCESS = 200;
    case CREATED = 201;
    case BAD_REQUEST = 400;

    case AUTHENTICATION_REQUIRED = 401;
    case AUTHORIZATION_ERROR = 403;
    case NOT_FOUND = 404;

    case SERVER_ERROR = 500;
}
