
import {addTwoNumbers, createListNodeFromArray, convertListNodeToArray} from './solution';
import {assert} from "chai";
import {ListNode} from './ListNode';

function testing(numbers1: number[], numbers2: number[], expected: number[]) {
    const node1 = createListNodeFromArray(numbers1);
    const node2 = createListNodeFromArray(numbers2);

    const resultNode = addTwoNumbers(node1, node2);
    if (resultNode) {
        assert.deepEqual(convertListNodeToArray(resultNode), expected);
    }
}

describe("ListNode", function () {
    it("can create itself from empty array", function () {
        assert.isNull(createListNodeFromArray([]));
    });
    it("can create itself from 1 element array", function () {
        const sourceArray = [1];
        const resultNode = createListNodeFromArray(sourceArray);
        assert.isNotNull(resultNode);
        assert.equal(resultNode?.val, sourceArray[0]);
        assert.isNull(resultNode?.next);
    });
    it("can create itself from multiple elements array", function () {
        const sourceArray = [1, 2];
        const resultNode = createListNodeFromArray(sourceArray);
        assert.isNotNull(resultNode);
        assert.equal(resultNode?.val, sourceArray[0]);
        assert.equal(resultNode?.next?.val, sourceArray[1]);
        assert.isNull(resultNode?.next?.next);
    });
    it("can convert itseft to array", function () {
        const sourceArray = [1, 2, 3];
        const sourceNode = createListNodeFromArray(sourceArray);
        if (sourceNode) {
            const resultArray = convertListNodeToArray(sourceNode);
            assert.deepEqual(resultArray, sourceArray);
        }
    });
});

describe("Fixed Tests", function() {
    it("works", function() {
        testing([3], [4], [7]);
        testing([2, 4, 3], [5, 6, 4], [7, 0, 8]);
    });
});
