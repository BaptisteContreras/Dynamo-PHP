<?php

namespace App\Tests\Application\Manager;

use App\Shared\Infrastructure\Persistence\Doctrine\Node;
use App\Tests\Application\AppTestCase;
use DataFixtures\Manager\NodeFixtures;
use DataFixtures\Manager\VirtualNodeFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class JoinControllerTest extends AppTestCase
{
    private const string URI = 'manager/join';
    private const array JSON_DATA = [
        'selfNode' => [
            'weight' => 3,
            'seed' => true,
            'label' => 'A',
            'host' => 'localhost',
            'networkPort' => 9003,
        ],
        'initialSeeds' => [],
        'config' => [
            'virtualNodeAttributionStrategy' => 'random',
        ],
    ];

    public function testJoinSuccess(): void
    {
        $this->databaseTool->loadFixtures();

        $this->client->followRedirects();
        $this->postJson(self::URI, self::JSON_DATA);

        self::assertResponseIsSuccessful();

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $nodes = $em->getRepository(Node::class)->findAll();

        self::assertCount(1, $nodes);

        /** @var Node $selfNode */
        $selfNode = current($nodes);

        self::assertCount(3, $selfNode->getVirtualNodes());
    }

    public function testJoinErrorAlreadyJoined(): void
    {
        $this->databaseTool->loadFixtures([
            NodeFixtures::class,
            VirtualNodeFixtures::class,
        ]);

        $this->postJson(self::URI, self::JSON_DATA);

        self::assertResponseStatusCodeSame(Response::HTTP_CONFLICT);
        self::assertJsonStringEqualsJsonFile(sprintf('%s/%s.json', __DIR__, __FUNCTION__), $this->client->getResponse()->getContent());
    }
}
