<?php

require './solution.php';

class Leetcode34Test extends \PHPUnit\Framework\TestCase
{
    public function testSolution()
    {
        $this->assertEquals([3,4], (new Solution())->searchRange([5,7,7,8,8,10], 8));
        $this->assertEquals([-1,-1], (new Solution())->searchRange([5,7,7,8,8,10], 6));
        $this->assertEquals([-1,-1], (new Solution())->searchRange([], 0));
        $this->assertEquals([2,2], (new Solution())->searchRange([0,1,2,3,4,4,4], 2));
        $this->assertEquals([14,15], (new Solution())->searchRange([0,0,1,1,1,2,4,4,4,4,5,5,5,6,8,8,9,9,10,10,10], 8));
        $this->assertEquals([-1,-1], (new Solution())->searchRange([0,0,1,1,1,4,5,5], 2));
        $this->assertEquals([12,12], (new Solution())->searchRange([0,1,1,2,2,2,2,2,2,2,2,2,3,4,4], 3));
    }
}