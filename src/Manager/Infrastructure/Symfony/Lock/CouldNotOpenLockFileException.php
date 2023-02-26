<?php

namespace App\Manager\Infrastructure\Symfony\Lock;

class CouldNotOpenLockFileException extends \Exception
{
    public function __construct(string $filePath)
    {
        parent::__construct(sprintf(
            'Could not open %s',
            $filePath
        ));
    }
}
