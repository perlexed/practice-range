
import {ListNode} from './ListNode';

/**
 * Definition for singly-linked list.
 * class ListNode {
 *     val: number
 *     next: ListNode | null
 *     constructor(val?: number, next?: ListNode | null) {
 *         this.val = (val===undefined ? 0 : val)
 *         this.next = (next===undefined ? null : next)
 *     }
 * }
 */

export function addTwoNumbers(l1: ListNode | null, l2: ListNode | null): ListNode | null {
    const resultArray = [];

    let nextOrderToAdd = 0;

    let l1Value = 0;
    let l2Value = 0;
    let sum = 0;

    do {
        l1Value = l1 !== null ? l1.val : 0;
        l2Value = l2 !== null ? l2.val : 0;
        sum = l1Value + l2Value + nextOrderToAdd;
        if (sum >= 10) {
            nextOrderToAdd = 1;
            sum = sum % 10;
        } else {
            nextOrderToAdd = 0;
        }

        resultArray.push(sum);

        l1 = l1 !== null ? l1.next : null;
        l2 = l2 !== null ? l2.next : null;
    } while (l1 !== null || l2 !== null)

    if (nextOrderToAdd) {
        resultArray.push(nextOrderToAdd);
    }

    return createListNodeFromArray(resultArray);
}

export function createListNodeFromArray(numbers: number[]): ListNode|null {
    if (!numbers.length) {
        return null;
    }

    const [firstNumber, ...restOfNumbers] = numbers;
    const firstNode = new ListNode(firstNumber);

    let previousNode = firstNode;

    restOfNumbers.forEach(currentNumber => {
        const newNode = new ListNode(currentNumber);
        previousNode.next = newNode;
        previousNode = newNode;
    });

    return firstNode;
}

export function convertListNodeToArray(node: ListNode): number[] {
    const resultArray = [node.val];

    let currentNode: ListNode = node;
    while (currentNode.next !== null) {
        currentNode = currentNode.next;
        resultArray.push(currentNode.val);
    }

    return resultArray;
}
