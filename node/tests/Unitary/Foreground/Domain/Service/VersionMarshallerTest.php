<?php

namespace App\Tests\Unitary\Foreground\Domain\Service;

use App\Foreground\Domain\Service\Version\VersionMarshaller;
use PHPUnit\Framework\TestCase;

class VersionMarshallerTest extends TestCase
{
    final public const NODE_1 = 'node1';
    final public const NODE_2 = 'node2';

    private VersionMarshaller $versionMarshaller;

    protected function setUp(): void
    {
        $this->versionMarshaller = new VersionMarshaller();
    }

    public function getTestTransformFromStringSuccessCases(): \Generator
    {
        yield 'Valid string and empty' => [
            'W10=',
            [],
        ];
        yield 'Valid string and one node' => [
            'W3sibm9kZTEiOjF9XQ==',
            [self::NODE_1 => 1],
        ];
        yield 'Valid string and multiple nodes' => [
            'W3sibm9kZTEiOjF9LHsibm9kZTEiOjIsIm5vZGUyIjozfSx7Im5vZGUyIjoyfV0=',
            [self::NODE_1 => 2, self::NODE_2 => 3],
        ];
    }

    /**
     * @dataProvider getTestTransformFromStringSuccessCases
     */
    public function testTransformFromString(string $stringVersion, array $expectedVectorResult): void
    {
        $resultClock = $this->versionMarshaller->transformFromString($stringVersion);

        $resultVector = $resultClock->getVector();

        self::assertCount(count($expectedVectorResult), $resultVector);
        self::assertEmpty(array_diff_key($expectedVectorResult, $resultVector));

        foreach ($expectedVectorResult as $expectedResultKey => $expectedResultValue) {
            self::assertEquals($expectedResultValue, $resultVector[$expectedResultKey]);
        }
    }
}
