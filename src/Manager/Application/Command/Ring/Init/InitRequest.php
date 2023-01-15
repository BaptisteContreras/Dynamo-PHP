<?php


namespace App\Manager\Application\Command\Ring\Init;


use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

final class InitRequest
{
    #[NotBlank]
    #[Choice(choices: [])]
    private ?string $allocationStrategyName = null;

    public function getAllocationStrategyName(): string
    {
        return $this->allocationStrategyName;
    }


}