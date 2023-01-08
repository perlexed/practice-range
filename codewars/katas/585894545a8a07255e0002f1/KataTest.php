<?php


use PHPUnit\Framework\TestCase;

/**
 * @link https://www.codewars.com/kata/585894545a8a07255e0002f1/train/php
 */

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
    $grid = new Grid();
    $startingPoint = Point::createFromLetter($letter);

    return $grid->findPossibleVariationsCount($startingPoint, $length);
}

class Point
{
    public int $x;
    public int $y;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public static function createFromLetter(string $letter): self
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

        $letterCoordinates = $lettersToCoordinates[$letter];

        return new self($letterCoordinates[0], $letterCoordinates[1]);
    }
}

class Grid
{
    const GRID_SIDE_SIZE = 3;
    public array $gridInstance;

    public function __construct()
    {
        $this->gridInstance = [
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ];
    }

    public function clone(): self
    {
        $grid = new self();
        $grid->gridInstance = $this->gridInstance;

        return $grid;
    }

    private function markPointOccupied($x, $y)
    {
        $this->gridInstance[$y][$x] = 1;
    }

    private function isPointFree(Point $point): bool
    {
        return $this->gridInstance[$point->y][$point->x] === 0;
    }

    public function findPossibleVariationsCount(Point $point, int $length): int
    {
        if ($length === 0 || $length > 9) {
            return 0;
        } elseif ($length === 1) {
            return 1;
        }

        $totalVariants = 0;
        $this->markPointOccupied($point->x, $point->y);

        $availablePoints = $this->getAvailableNextPoints($point);

        $length--;

        foreach ($availablePoints as $availablePoint) {
            $clonedGrid = $this->clone();
            $totalVariants += $clonedGrid->findPossibleVariationsCount($availablePoint, $length);
        }

        return $totalVariants;
    }

    private static function isPointInGrid(Point $point): bool
    {
        return self::isInGrid($point->x) && self::isInGrid($point->y);
    }

    private static function isInGrid(int $coordinate): bool
    {
        return $coordinate >= 0 && $coordinate < self::GRID_SIDE_SIZE;
    }

    public function getAvailableNextPoints(Point $point): array
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
            $nextPoint = new Point(
                $point->x + $variation['xDiff'],
                $point->y + $variation['yDiff'],
            );

            if (self::isPointInGrid($nextPoint)) {
                if ($this->isPointFree($nextPoint)) {
                    $availablePoints[] = $nextPoint;
                    continue;
                }

                $nextPoint = new Point(
                    $nextPoint->x + $variation['xDiff'],
                    $nextPoint->y + $variation['yDiff'],
                );

                if (self::isPointInGrid($nextPoint) && $this->isPointFree($nextPoint)) {
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
            $nextPoint = new Point(
                $point->x + $inclinedVariation['xDiff'],
                $point->y + $inclinedVariation['yDiff'],
            );

            if (self::isPointInGrid($nextPoint) && $this->isPointFree($nextPoint)) {
                $availablePoints[] = $nextPoint;
            }
        }

        return $availablePoints;
    }
}


class KataTest extends TestCase
{
    public function testGridClass()
    {
        $grid = new Grid();
        $this->assertNotNull($grid);

        $availablePoints = $grid->getAvailableNextPoints(new Point(0, 0));
        $this->assertCount(5, $availablePoints);

        $availablePoints = $grid->getAvailableNextPoints(new Point(1, 1));
        $this->assertCount(8, $availablePoints);
    }

    public function testPointCreation()
    {
        $point = Point::createFromLetter('C');
        $this->assertEquals(2, $point->x);
        $this->assertEquals(0, $point->y);
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
