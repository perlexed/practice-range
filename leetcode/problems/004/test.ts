import {assert} from "chai";
import {findMedianSortedArrays} from './solution';

describe('target function', function () {
    it('passes the examples', function () {
        assert.equal(findMedianSortedArrays([1, 3], [2]), 2);
        assert.equal(findMedianSortedArrays([1, 2], [3, 4]), 2.5);
        assert.equal(findMedianSortedArrays([], [2, 3]), 2.5);
        assert.equal(findMedianSortedArrays([], [2, 3, 4]), 3);
    });
});
