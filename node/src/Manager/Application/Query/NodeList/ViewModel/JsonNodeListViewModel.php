<?php

namespace App\Manager\Application\Query\NodeList\ViewModel;

use App\Manager\Domain\Model\Aggregate\Node\Node;
use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;
use Symfony\Component\Serializer\Annotation\Ignore;

abstract class JsonNodeListViewModel extends ViewModel implements JsonViewModelInterface
{
    /**
     * @param array<Node> $nodes
     */
    public static function success(array $nodes): self
    {
        return new JsonSuccessViewModel($nodes);
    }

    public static function badFilter(): self
    {
        return new JsonBadFiltersViewModel();
    }

    #[Ignore]
    public function getCode(): int
    {
        return parent::getCode();
    }
}
