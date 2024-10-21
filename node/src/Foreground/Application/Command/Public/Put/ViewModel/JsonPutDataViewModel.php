<?php

namespace App\Foreground\Application\Command\Public\Put\ViewModel;

use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;
use Symfony\Component\Serializer\Annotation\Ignore;

abstract class JsonPutDataViewModel extends ViewModel implements JsonViewModelInterface
{
    public static function success(): self
    {
        return new JsonSuccessViewModel();
    }

    #[Ignore]
    public function getCode(): int
    {
        return parent::getCode();
    }
}
