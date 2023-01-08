
import {G964} from './solution';
import {assert, expect} from "chai";
import {getCombinations} from "./solution.improper";

describe('getCombinations', function () {
    it('works fine', function () {
        const exampleSets = [
            ['a1', 'a2', 'a3'],
            ['b1', 'b2'],
        ];
        const expectedCombinations = [
            ['a1', 'b1'],
            ['a2', 'b1'],
            ['a3', 'b1'],
            ['a1', 'b2'],
            ['a2', 'b2'],
            ['a3', 'b2'],
        ];
        const actualCombinations = getCombinations(exampleSets);
        assert.lengthOf(actualCombinations, expectedCombinations.length);

        // Shallow elements comparison
        actualCombinations.forEach((combinationElement, elementIndex) => {
            expect(combinationElement).to.have.same.members(expectedCombinations[elementIndex]);
        });
    });
});


describe("Fixed", function() {
    it("part", function() {
        assert.equal(G964.part(8), "Range: 17 Average: 8.29 Median: 7.50");
        assert.equal(G964.part(9), "Range: 26 Average: 11.17 Median: 9.50");
        assert.equal(G964.part(10), "Range: 35 Average: 15.00 Median: 14.00");
        assert.equal(G964.part(35), "Range: 354293 Average: 20088.78 Median: 4704.00");
    });
});