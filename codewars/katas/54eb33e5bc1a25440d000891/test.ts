
import {G964} from './solution';
import {assert} from "chai";

function testing(n: number, expected: number[]) {
    assert.deepEqual(G964.decompose(n), expected);
}

describe("Fixed Tests", function() {
    it("Basic tests decompose", function() {
        testing(5, [3,4]);
        testing(50, [1,3,5,8,49]);
        testing(44, [2,3,5,7,43]);
    });
});
