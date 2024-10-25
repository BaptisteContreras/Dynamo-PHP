<?php

namespace App\Foreground\Application\Command\Public\Put\Request;

final readonly class PutRequest
{
    private PutItem $item;

    public function __construct(PutItem $item)
    {
        $this->item = $item;
    }

    public function getItem(): PutItem
    {
        return $this->item;
    }
}
