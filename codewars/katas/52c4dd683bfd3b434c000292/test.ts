import { isInteresting } from './solution';
import { assert } from "chai";

function test(n: number, awesome: number[], expected: number) {
    assert.strictEqual(isInteresting(n, awesome), expected);
}

describe("solution", function(){
    it('should work, dangit!', function() {
        assert.strictEqual(isInteresting(1111, []), 2);
        assert.strictEqual(isInteresting(1109, []), 1);
        test(3, [1337, 256],     0);
        test(1336, [1337, 256],  1);
        test(1337, [1337, 256],  2);
        test(11208, [1337, 256], 0);
        test(11209, [1337, 256], 1);
        test(11211, [1337, 256], 2);
    });
});