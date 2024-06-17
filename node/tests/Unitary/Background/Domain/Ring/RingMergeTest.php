<?php

namespace App\Tests\Unitary\Background\Domain\Ring;

use App\Background\Domain\Model\Aggregate\History\History;
use App\Background\Domain\Model\Aggregate\Ring\Collection\NodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\VirtualNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Background\Domain\Model\Aggregate\Ring\Ring;
use App\Background\Domain\Model\Aggregate\Ring\VirtualNode;
use App\Shared\Domain\Const\NodeState;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV7;

class RingMergeTest extends TestCase
{
    public static function getData(): \Generator
    {
        yield 'simple merge' => [
            [
                [
                    'id' => UuidV7::fromString('01900395-93bc-72a6-bf1b-0b66e93311ca'),
                    'host' => '127.0.0.1',
                    'networkPort' => 80,
                    'state' => NodeState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                    'weight' => 1,
                    'seed' => true,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-11 21:28:00'),
                    'label' => 'A',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => UuidV7::fromString('01901352-03dc-7cdf-8bae-46478df51aeb'),
                            'label' => 'A1',
                            'slot' => 200,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
                [
                    'id' => UuidV7::fromString('01901353-ceb7-71ba-a89c-794a45e538ce'),
                    'host' => 'localhost',
                    'networkPort' => 8080,
                    'state' => NodeState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-12 21:28:00'),
                    'weight' => 9,
                    'seed' => false,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-12 21:28:00'),
                    'label' => 'B',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => UuidV7::fromString('01901353-de44-7d5a-a0bd-1dadfee1fc05'),
                            'label' => 'B1',
                            'slot' => 50,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-12 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
                '01901352-03dc-7cdf-8bae-46478df51aeb',
                '01901353-de44-7d5a-a0bd-1dadfee1fc05',
            ],
            [
            ],
        ];
    }

    /**
     * @dataProvider getData
     */
    public function testSuccessful(
        array $localRingNodes,
        array $remoteRingNodes,
        array $internalRing,
        array $disabledVirtualNodes
    ): void {
        $localRing = new Ring(new NodeCollection(
            array_map(fn (array $nodeData) => $this->dataArrayToNode($nodeData), $localRingNodes)
        ));

        $remoteRing = new Ring(new NodeCollection(
            array_map(fn (array $nodeData) => $this->dataArrayToNode($nodeData), $remoteRingNodes)
        ));

        $localRing->merge($remoteRing, History::createEmpty());

        $localVirtualNodes = $localRing->getVirtualNodes();
        $localDisabledVirtualNodes = $localRing->getDisabledVirtualNodes();

        self::assertCount(count($internalRing), $localVirtualNodes);
        self::assertCount(count($disabledVirtualNodes), $localDisabledVirtualNodes);

        foreach ($internalRing as $expectActiveVirtualNodeId) {
            self::assertTrue($localVirtualNodes->keyExists($expectActiveVirtualNodeId));
        }

        foreach ($disabledVirtualNodes as $expectDisabledVirtualNodeId) {
            self::assertTrue($localDisabledVirtualNodes->keyExists($expectDisabledVirtualNodeId));
        }
    }

    private function dataArrayToNode(array $nodeData): Node
    {
        $virtualNodes = VirtualNodeCollection::createEmpty();

        $node = new Node(
            $nodeData['id'],
            $nodeData['host'],
            $nodeData['networkPort'],
            $nodeData['state'],
            $nodeData['joinedAt'],
            $nodeData['weight'],
            $nodeData['seed'],
            $nodeData['updatedAt'],
            $nodeData['label'],
            $virtualNodes,
            $nodeData['local']
        );

        $virtualNodes->merge(
            new VirtualNodeCollection(
                array_map(fn (array $virtualNodeData) => $this->dataArrayToVirtualNode($virtualNodeData, $node), $nodeData['virtualNodes'])
            )
        );

        return $node;
    }

    private function dataArrayToVirtualNode(array $virtualNodeData, Node $node): VirtualNode
    {
        return new VirtualNode(
            $virtualNodeData['id'],
            $virtualNodeData['label'],
            $virtualNodeData['slot'],
            $virtualNodeData['createdAt'],
            $node,
            $virtualNodeData['active']
        );
    }
}
