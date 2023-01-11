<?php

namespace App\Manager\Application\Command\Register;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterCommandHandler
{
    /**         Properties         **/

    /**         Constructor         **/
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    /**         Methods         **/
    public function __invoke(RegisterRequest $registerRequest, AbstractRegisterWorkerPresenter $abstractRegisterWorkerPresenter)
    {
        // Validate Request
        // Create WorkerInformations
        // Apply Hash
    }
}
