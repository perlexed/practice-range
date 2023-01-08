<?php


use PHPUnit\Framework\TestCase;

/**
 * @link https://www.codewars.com/kata/585894545a8a07255e0002f1/train/php
 */

const GRID_SIDE_SIZE = 3;

/**
 * A B C
 * D E F
 * G H I
 *
 * @param string $letter
 * @param int $length
 * @return int
 */
function countPatternsFrom(string $letter, int $length): int {
    $grid = [
        [0, 0, 0],
        [0, 0, 0],
        [0, 0, 0],
    ];
    $startingPoint = createPointFromLetter($letter);

    return findPossibleVariationsCount($grid, $startingPoint, $length);
}

/**
 * @param string $letter
 * @return int[]
 */
function createPointFromLetter(string $letter): array
{
    $lettersToCoordinates = [
        'A' => [0, 0],
        'B' => [1, 0],
        'C' => [2, 0],
        'D' => [0, 1],
        'E' => [1, 1],
        'F' => [2, 1],
        'G' => [0, 2],
        'H' => [1, 2],
        'I' => [2, 2],
    ];

    return $lettersToCoordinates[$letter];
}

function findPossibleVariationsCount(array $grid, array $point, int $length): int
{
    if ($length === 0 || $length > 9) {
        return 0;
    } elseif ($length === 1) {
        return 1;
    }

    $totalVariants = 0;
    $grid[$point[1]][$point[0]] = 1;

    $availablePoints = getAvailableNextPoints($grid, $point);

    $length--;

    foreach ($availablePoints as $availablePoint) {
        $totalVariants += findPossibleVariationsCount($grid, $availablePoint, $length);
    }

    return $totalVariants;
}

function getAvailableNextPoints(array $grid, array $point): array
{
    $availablePoints = [];

    $variations = [
        ['xDiff' => -1, 'yDiff' => 0],
        ['xDiff' => 1, 'yDiff' => 0],

        ['xDiff' => 0, 'yDiff' => -1],
        ['xDiff' => 0, 'yDiff' => 1],

        ['xDiff' => -1, 'yDiff' => 1],
        ['xDiff' => 1, 'yDiff' => -1],

        ['xDiff' => 1, 'yDiff' => 1],
        ['xDiff' => -1, 'yDiff' => -1],
    ];

    foreach ($variations as $variation) {
        $nextPoint = [
            $point[0] + $variation['xDiff'],
            $point[1] + $variation['yDiff'],
        ];

        if (isPointInGrid($nextPoint)) {
            if (isPointFree($grid, $nextPoint)) {
                $availablePoints[] = $nextPoint;
                continue;
            }

            $nextPoint = [
                $nextPoint[0] + $variation['xDiff'],
                $nextPoint[1] + $variation['yDiff'],
            ];

            if (isPointInGrid($nextPoint) && isPointFree($grid, $nextPoint)) {
                $availablePoints[] = $nextPoint;
            }
        }
    }

    $inclinedVariations = [
        ['xDiff' => -1, 'yDiff' => -2],
        ['xDiff' => 1, 'yDiff' => -2],

        ['xDiff' => -1, 'yDiff' => 2],
        ['xDiff' => 1, 'yDiff' => 2],

        ['xDiff' => -2, 'yDiff' => -1],
        ['xDiff' => -2, 'yDiff' => 1],

        ['xDiff' => 2, 'yDiff' => -1],
        ['xDiff' => 2, 'yDiff' => 1],
    ];

    foreach ($inclinedVariations as $inclinedVariation) {
        $nextPoint = [
            $point[0] + $inclinedVariation['xDiff'],
            $point[1] + $inclinedVariation['yDiff'],
        ];

        if (isPointInGrid($nextPoint) && isPointFree($grid, $nextPoint)) {
            $availablePoints[] = $nextPoint;
        }
    }

    return $availablePoints;

}

function isPointInGrid(array $point): bool
{
    return ($point[0] >= 0 && $point[0] < GRID_SIDE_SIZE)
        && ($point[1] >= 0 && $point[1] < GRID_SIDE_SIZE);
}

function isPointFree(array $grid, array $point): bool
{
    return $grid[$point[1]][$point[0]] === 0;
}


class KataTestFunctions extends TestCase
{
    public function testGridClass()
    {
        $grid = [
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ];
        $availablePoints = getAvailableNextPoints($grid, [0, 0]);
        $this->assertCount(5, $availablePoints);

        $availablePoints = getAvailableNextPoints($grid, [1, 1]);
        $this->assertCount(8, $availablePoints);
    }

    public function testPointCreation()
    {
        $point = createPointFromLetter('C');
        $this->assertEquals(2, $point[0]);
        $this->assertEquals(0, $point[1]);
    }

    public function testBasicTests()
    {
        $this->assertEquals(0, countPatternsFrom('A', 0));
        $this->assertEquals(0, countPatternsFrom('A', 10));
        $this->assertEquals(1, countPatternsFrom('B', 1));
        $this->assertEquals(5, countPatternsFrom('C', 2));
        $this->assertEquals(37, countPatternsFrom('D', 3));
        $this->assertEquals(256, countPatternsFrom('E', 4));
        $this->assertEquals(23280, countPatternsFrom('E', 8));
    }
}
