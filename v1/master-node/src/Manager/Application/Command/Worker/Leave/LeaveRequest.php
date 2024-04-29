<?php

namespace App\Manager\Application\Command\Worker\Leave;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

final class LeaveRequest
{
    private const MIN_ID = 1;
    private const MAX_ID = 999999999;

    #[NotBlank]
    #[Range(
        min: self::MIN_ID,
        max: self::MAX_ID,
    )]
    private ?int $workerId = null;

    public static function build(?int $workerId): self
    {
        return (new self())
            ->setWorkerId($workerId);
    }

    /**
     * Phpstan spots a type mismatch but this method must be called after the validation of this object.
     * This phpstan error is ignored in the baseline for the moment.
     */
    public function getWorkerId(): int
    {
        return $this->workerId;
    }

    public function setWorkerId(?int $workerId): self
    {
        $this->workerId = $workerId;

        return $this;
    }
}
