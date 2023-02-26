<?php

namespace App\Manager\Infrastructure\Symfony\Lock;

class CouldNotWriteLockFileException extends \Exception
{
    public function __construct(string $filePath)
    {
        parent::__construct(sprintf(
            'Could not write to %s',
            $filePath
        ));
    }
}
