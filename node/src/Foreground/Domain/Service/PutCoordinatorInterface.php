<?php

namespace App\Foreground\Domain\Service;

interface PutCoordinatorInterface
{
    public function isLocalNodeOwnerOf(int $ringKey): bool;

    public function forwardWrite();
}
