<?php

namespace App\Foreground\Application\Command\Public\Put\Request;

final readonly class PutRequest
{
    private string $key;

    private PutData $data;

    public function __construct(string $key, PutData $data)
    {
        $this->key = $key;
        $this->data = $data;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getData(): PutData
    {
        return $this->data;
    }
}
