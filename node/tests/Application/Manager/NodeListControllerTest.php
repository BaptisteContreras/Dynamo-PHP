<?php

namespace App\Tests\Application\Manager;

use App\Tests\Application\AppTestCase;
use DataFixtures\Manager\NodeFixtures;
use DataFixtures\Manager\VirtualNodeFixtures;

class NodeListControllerTest extends AppTestCase
{
    public function testGetNodeListSuccess(): void
    {
        $this->databaseTool->loadFixtures([
            NodeFixtures::class,
            VirtualNodeFixtures::class,
        ]);

        $this->client->request('GET', 'manager/nodes');

        self::assertResponseIsSuccessful();
        self::assertJsonStringEqualsJsonFile(sprintf('%s/%s.json', __DIR__, __FUNCTION__), $this->client->getResponse()->getContent());
    }

    public function testGetNodeListWithFilterSuccess(): void
    {
        $this->databaseTool->loadFixtures([
            NodeFixtures::class,
            VirtualNodeFixtures::class,
        ]);

        $this->client->request('GET', 'manager/nodes?seed=true');

        self::assertResponseIsSuccessful();
        self::assertJsonStringEqualsJsonFile(sprintf('%s/%s.json', __DIR__, __FUNCTION__), $this->client->getResponse()->getContent());
    }

    public function testGetNodeListWithFilterSuccess2(): void
    {
        $this->databaseTool->loadFixtures([
            NodeFixtures::class,
            VirtualNodeFixtures::class,
        ]);

        $this->client->request('GET', 'manager/nodes?states[]=1');

        self::assertResponseIsSuccessful();
        self::assertJsonStringEqualsJsonFile(sprintf('%s/%s.json', __DIR__, __FUNCTION__), $this->client->getResponse()->getContent());
    }
}
