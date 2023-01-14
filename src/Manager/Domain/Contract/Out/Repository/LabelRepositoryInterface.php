<?php

namespace App\Manager\Domain\Contract\Out\Repository;

use App\Manager\Domain\Model\Dto\Label;

interface LabelRepositoryInterface
{
    public function add(Label $label, bool $flush): void;

    public function remove(Label $label, bool $flush): void;

    public function update(Label $label, bool $flush): void;
}
