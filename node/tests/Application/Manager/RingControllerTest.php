<?php

namespace App\Tests\Application\Manager;

use App\Tests\Application\AppTestCase;

class RingControllerTest extends AppTestCase
{
    public function getRingSuccess(): void
    {
        $this->databaseTool->loadFixtures();
    }
}
