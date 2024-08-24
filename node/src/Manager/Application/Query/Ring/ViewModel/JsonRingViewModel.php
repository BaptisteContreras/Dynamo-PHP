<?php

namespace App\Manager\Application\Query\Ring\ViewModel;

use App\Manager\Domain\Model\Aggregate\Ring\Ring;
use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;
use Symfony\Component\Serializer\Annotation\Ignore;

abstract class JsonRingViewModel extends ViewModel implements JsonViewModelInterface
{
    public static function success(Ring $ring): self
    {
        return new JsonSuccessViewModel($ring);
    }

    #[Ignore]
    public function getCode(): int
    {
        return parent::getCode();
    }
}
