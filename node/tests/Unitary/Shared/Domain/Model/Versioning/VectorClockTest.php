<?php

namespace App\Tests\Unitary\Shared\Domain\Model\Versioning;

use App\Shared\Domain\Model\Versioning\ClockOrder;
use App\Shared\Domain\Model\Versioning\VectorClock;
use PHPUnit\Framework\TestCase;

class VectorClockTest extends TestCase
{
    final public const Sx = 'Sx';
    final public const Sy = 'Sy';
    final public const Sz = 'Sz';
    final public const Sw = 'Sw';

    public function getCompareIdenticalCases(): \Generator
    {
        yield 'Same vector' => [
            new VectorClock([self::Sx => 1, self::Sy => 2]),
            new VectorClock([self::Sy => 2, self::Sx => 1]),
            ClockOrder::IDENTICAL,
        ];

        yield 'Empty clocks' => [
            new VectorClock([]),
            new VectorClock([]),
            ClockOrder::IDENTICAL,
        ];
    }

    public function getCompareCausalityCases(): \Generator
    {
        yield 'Same nodes in vector' => [
            new VectorClock([self::Sx => 2, self::Sy => 2]),
            new VectorClock([self::Sx => 1, self::Sy => 2]),
        ];
        yield 'Same nodes in vector 2' => [
            new VectorClock([self::Sx => 1, self::Sy => 3]),
            new VectorClock([self::Sx => 1, self::Sy => 2]),
        ];
        yield 'One clock has less nodes' => [
            new VectorClock([self::Sx => 2, self::Sy => 2]),
            new VectorClock([self::Sx => 1]),
        ];
        yield 'One clock has less nodes 2' => [
            new VectorClock([self::Sx => 2, self::Sy => 1, self::Sz => 1]),
            new VectorClock([self::Sx => 1]),
        ];
        yield 'One clock has less nodes 3' => [
            new VectorClock([self::Sx => 2, self::Sy => 1, self::Sz => 1]),
            new VectorClock([self::Sx => 1, self::Sy => 1]),
        ];
    }

    public function getCompareConcurrentCases(): \Generator
    {
        yield 'concurrent with no similar node' => [
            new VectorClock([self::Sx => 2]),
            new VectorClock([self::Sy => 2]),
            ClockOrder::CONCURRENT,
        ];
        yield 'concurrent with one node' => [
            new VectorClock([self::Sx => 2, self::Sz => 1]),
            new VectorClock([self::Sx => 2, self::Sy => 1]),
            ClockOrder::CONCURRENT,
        ];
        yield 'concurrent with one node 2' => [
            new VectorClock([self::Sx => 2, self::Sy => 4]),
            new VectorClock([self::Sx => 2, self::Sy => 1, self::Sz => 1]),
            ClockOrder::CONCURRENT,
        ];
        yield 'concurrent with one node 3' => [
            new VectorClock([self::Sx => 2, self::Sy => 4]),
            new VectorClock([self::Sx => 3, self::Sy => 1, self::Sz => 1]),
            ClockOrder::CONCURRENT,
        ];
        yield 'concurrent with one node 4' => [
            new VectorClock([self::Sx => 3, self::Sy => 4]),
            new VectorClock([self::Sx => 3, self::Sy => 1, self::Sz => 1]),
            ClockOrder::CONCURRENT,
        ];
        yield 'concurrent with one node 5' => [
            new VectorClock([self::Sx => 4, self::Sy => 4]),
            new VectorClock([self::Sx => 3, self::Sy => 1, self::Sz => 1]),
            ClockOrder::CONCURRENT,
        ];
    }

    public function getTickCases(): \Generator
    {
        yield 'Tick an empty clock' => [
            new VectorClock([]),
            self::Sx,
            [self::Sx => 1],
        ];
        yield 'Tick existing node in non empty clock' => [
            new VectorClock([self::Sx => 1, self::Sy => 1, self::Sz => 3]),
            self::Sy,
            [self::Sx => 1, self::Sy => 2, self::Sz => 3],
        ];
        yield 'Tick non existing node in non empty clock' => [
            new VectorClock([self::Sx => 1, self::Sz => 3]),
            self::Sy,
            [self::Sx => 1, self::Sy => 1, self::Sz => 3],
        ];
    }

    public function getMergeCases(): \Generator
    {
        yield 'Merge two empty clock' => [
            [
                VectorClock::empty(),
                VectorClock::empty(),
            ],
            [],
        ];
        yield 'Merge one empty clock' => [
            [
                new VectorClock([self::Sx => 2]),
                VectorClock::empty(),
            ],
            [self::Sx => 2],
        ];
        yield 'Merge identical clocks' => [
            [
                new VectorClock([self::Sx => 2, self::Sy => 1]),
                new VectorClock([self::Sx => 2, self::Sy => 1]),
                new VectorClock([self::Sx => 2, self::Sy => 1]),
            ],
            [self::Sx => 2, self::Sy => 1],
        ];
        yield 'Merge clocks with only one node' => [
            [
                new VectorClock([self::Sx => 2]),
                new VectorClock([self::Sy => 1]),
                new VectorClock([self::Sz => 5]),
            ],
            [self::Sx => 2, self::Sy => 1, self::Sz => 5],
        ];
        yield 'Merge concurrent clocks with multiple nodes' => [
            [
                new VectorClock([self::Sx => 2, self::Sy => 5]),
                new VectorClock([self::Sx => 2, self::Sz => 5]),
                new VectorClock([self::Sx => 5, self::Sy => 1, self::Sz => 3]),
                new VectorClock([self::Sx => 5, self::Sy => 1, self::Sz => 2]),
                new VectorClock([self::Sx => 4, self::Sy => 1, self::Sz => 2]),
                new VectorClock([self::Sx => 4, self::Sy => 1, self::Sz => 3]),
                new VectorClock([self::Sw => 2]),
                new VectorClock([self::Sx => 4]),
            ],
            [self::Sx => 5, self::Sy => 5, self::Sz => 5, self::Sw => 2],
        ];
    }

    /**
     * @dataProvider getCompareIdenticalCases
     * @dataProvider getCompareConcurrentCases
     */
    public function testCompare(VectorClock $clock1, VectorClock $clock2, ClockOrder $expectedClockOrder): void
    {
        self::assertEquals($expectedClockOrder, $clock1->compare($clock2));
    }

    /**
     * @dataProvider getCompareCausalityCases
     */
    public function testCompareCausality(VectorClock $clockAfter, VectorClock $clockBefore): void
    {
        self::assertEquals(ClockOrder::HAPPEN_AFTER, $clockAfter->compare($clockBefore));
        self::assertEquals(ClockOrder::HAPPEN_BEFORE, $clockBefore->compare($clockAfter));
    }

    /**
     * @dataProvider getTickCases
     */
    public function testTick(VectorClock $clock, string $nodeToTick, array $expectedVectorResult): void
    {
        $clock->tick($nodeToTick);

        $resultVector = $clock->getVector();

        self::assertCount(count($expectedVectorResult), $resultVector);
        self::assertEmpty(array_diff_key($expectedVectorResult, $resultVector));

        foreach ($expectedVectorResult as $expectedResultKey => $expectedResultValue) {
            self::assertEquals($expectedResultValue, $resultVector[$expectedResultKey]);
        }
    }

    /**
     * @dataProvider getMergeCases
     */
    public function testMerge(array $clocksToMerge, array $expectedVectorResult): void
    {
        $clockResult = VectorClock::merge(...$clocksToMerge);

        $resultVector = $clockResult->getVector();

        self::assertCount(count($expectedVectorResult), $resultVector);
        self::assertEmpty(array_diff_key($expectedVectorResult, $resultVector));

        foreach ($expectedVectorResult as $expectedResultKey => $expectedResultValue) {
            self::assertEquals($expectedResultValue, $resultVector[$expectedResultKey]);
        }
    }
}
