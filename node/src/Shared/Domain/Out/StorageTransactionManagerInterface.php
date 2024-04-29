<?php

namespace App\Shared\Domain\Out;

interface StorageTransactionManagerInterface
{
    public function flush(): void;
}
