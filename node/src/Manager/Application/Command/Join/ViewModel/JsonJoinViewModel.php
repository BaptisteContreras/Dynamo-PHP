<?php

namespace App\Manager\Application\Command\Join\ViewModel;

use App\Manager\Domain\Model\Aggregate\Node\Node;
use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;
use Symfony\Component\Serializer\Annotation\Ignore;

abstract class JsonJoinViewModel extends ViewModel implements JsonViewModelInterface
{
    public static function success(Node $node): self
    {
        return new JsonSuccessViewModel($node);
    }

    #[Ignore]
    public function getCode(): int
    {
        return parent::getCode();
    }
}
