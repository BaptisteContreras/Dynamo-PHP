<?php

namespace App\Tests\Application\Manager;

use App\Tests\Application\AppTestCase;
use DataFixtures\Manager\NodeFixtures;
use DataFixtures\Manager\VirtualNodeFixtures;

class RingControllerTest extends AppTestCase
{
    public function testGetRingSuccess(): void
    {
        $this->databaseTool->loadFixtures([
            NodeFixtures::class,
            VirtualNodeFixtures::class,
        ]);

        $this->client->followRedirects();
        $this->client->request('GET', 'manager/ring');

        self::assertResponseIsSuccessful();
        self::assertJsonStringEqualsJsonFile(sprintf('%s/%s.json', __DIR__, __FUNCTION__), $this->client->getResponse()->getContent());
    }
}
