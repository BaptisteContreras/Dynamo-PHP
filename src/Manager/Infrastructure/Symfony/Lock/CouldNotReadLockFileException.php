<?php

namespace App\Manager\Infrastructure\Symfony\Lock;

class CouldNotReadLockFileException extends \Exception
{
    public function __construct(string $filePath)
    {
        parent::__construct(sprintf(
            'Could not read the content of %s',
            $filePath
        ));
    }
}
