<?php
use PHPUnit\Framework\TestCase;

function stringifyRange(array $range): string {
    $rangeLength = count($range);

    switch ($rangeLength) {
        case 1:
            return (string) $range[0];
        case 2:
            return $range[0] . ',' . $range[1];
        default:
            return $range[0] . '-' . $range[$rangeLength - 1];
    }
}

/**
 * @link https://www.codewars.com/kata/51ba717bb08c1cd60f00002f/train/php
 * @param array $list
 * @return string
 */
function solution(array $list): string
{
    $currentRange = [array_shift($list)];
    $resultElements = [];

    foreach ($list as $currentNumber) {
        $lastRangeElement = $currentRange[count($currentRange) - 1];
        if ($currentNumber - $lastRangeElement > 1) {
            $resultElements[] = stringifyRange($currentRange);
            $currentRange = [];
        }

        $currentRange[] = $currentNumber;
    }
    $resultElements[] = stringifyRange($currentRange);

    return implode(',', $resultElements);
}

class ExampleTest extends TestCase
{
    /**
     * @test
     */
    public function example(): void
    {
        self::assertSame(
            '-6,-3-1,3-5,7-11,14,15,17-20',
            solution([-6, -3, -2, -1, 0, 1, 3, 4, 5, 7, 8, 9, 10, 11, 14, 15, 17, 18, 19, 20])
        );
    }
}
