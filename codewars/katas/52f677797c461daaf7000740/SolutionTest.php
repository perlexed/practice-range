<?php

use PHPUnit\Framework\TestCase;

function solution(array $numbers): int
{
    $sourceArrayLength = count($numbers);

    do {
        $wasDifferenceOfOneFound = decreaseNumbers($numbers);

        // If the difference is 1 then the answer is [1, 1, ...length] = count($numbers)
        if ($wasDifferenceOfOneFound) {
            return $sourceArrayLength;
        }
    }
    // Repeat until there are no duplicates (the single number remains)
    while (count($numbers) > 1);

    return $sourceArrayLength * $numbers[0];
}

/**
 * Subtract minimal number from other numbers, removing duplicates
 *
 * @param array $numbers
 * @return bool whether difference of 1 was found
 */
function decreaseNumbers(array &$numbers): bool
{
    sort($numbers);

    $numberToTestWith = array_shift($numbers);
    $newArray = [$numberToTestWith];

    foreach ($numbers as $contextNumber) {
        $multiplier = (int) floor($contextNumber / $numberToTestWith);
        $difference = $contextNumber - ($numberToTestWith * $multiplier);

        if ($difference === 0) {
            continue;
        }
        if ($difference === 1) {
            return true;
        }

        $newArray[] = $difference;
    }

    $numbers = $newArray;
    return false;
}

class SolutionTest extends TestCase
{
    public function testExample() {
        $this->assertSame(9, solution([6, 9, 21]));
    }

    public function testHeavy()
    {
        $set = [];
        for ($numberIndex = 0; $numberIndex < 5000; $numberIndex++) {
            $set[] = mt_rand(5, 100);
        }
        $this->assertSame(5000, solution($set));
    }
}