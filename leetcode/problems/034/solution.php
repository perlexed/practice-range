<?php

class Solution {

    /**
     * @param Integer[] $nums
     * @param Integer $target
     * @return Integer[]
     */
    function searchRange($nums, $target) {
        $numsLength = count($nums);
        if (!$numsLength) {
            return [-1, -1];
        }
        $firstElementKey = array_key_first($nums);
        $lastElementKey = array_key_last($nums);

        if ($numsLength === 1 && array_values($nums)[0] !== $target) {
            return [-1, -1];
        }

        if ($nums[$firstElementKey] === $target) {
            $endIndex = $firstElementKey;
            while (isset($nums[$endIndex + 1]) && $nums[$endIndex + 1] === $target) {
                $endIndex++;
            }
            return [$firstElementKey, $endIndex];
        }

        if ($nums[$lastElementKey] === $target) {
            $startIndex = $lastElementKey;
            while (isset($nums[$startIndex - 1]) && $nums[$startIndex - 1] === $target) {
                $startIndex--;
            }
            return [$startIndex, $lastElementKey];
        }

        $middleIndexRelative = (int) floor($numsLength / 2);
        $middleIndex = array_keys($nums)[$middleIndexRelative];
        $middleNum = $nums[$middleIndex];

        if ($nums[$middleIndex] === $target) {
            $startIndex = $middleIndex;
            while (isset($nums[$startIndex - 1]) && $nums[$startIndex - 1] === $target) {
                $startIndex--;
            }
            $endIndex = $middleIndex;
            while (isset($nums[$endIndex + 1]) && $nums[$endIndex + 1] === $target) {
                $endIndex++;
            }
            return [$startIndex, $endIndex];
        }

        if ($middleNum > $target) {
            return $this->searchRange(array_slice($nums, 0, $middleIndexRelative, true), $target);
        } else {
            return $this->searchRange(array_slice($nums, $middleIndexRelative, $numsLength,true), $target);
        }
    }
}
