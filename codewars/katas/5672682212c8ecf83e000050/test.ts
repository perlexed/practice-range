// import { dblLinear } from './solution_2_1';
import { dblLinear } from './solution_5';
import { assert } from "chai";

describe("Fixed Tests", function() {
    it("Basic tests maxRot", function() {
        assert.strictEqual(dblLinear(4), 9);
        assert.strictEqual(dblLinear(10), 22);
        assert.strictEqual(dblLinear(20), 57);
        assert.strictEqual(dblLinear(30), 91);
        assert.strictEqual(dblLinear(500), 3355);
        assert.strictEqual(dblLinear(14239), 242613);
        assert.strictEqual(dblLinear(60000), 1511311);
    });
});