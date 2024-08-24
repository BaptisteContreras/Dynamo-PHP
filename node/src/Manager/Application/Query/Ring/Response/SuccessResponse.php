<?php

namespace App\Manager\Application\Query\Ring\Response;

use App\Manager\Domain\Model\Aggregate\Ring\Ring;

final class SuccessResponse extends RingResponse
{
    public function __construct(private Ring $ring)
    {
    }

    public function getRing(): Ring
    {
        return $this->ring;
    }
}
