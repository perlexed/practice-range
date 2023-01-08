import {calc, getBracketsBlocks} from './solution';
import { expect } from "chai";
import {parseExpressionIntoNode, OperationsEnum, calculateMixedNodeResult} from "./solution";

describe('expression parser',  () => {
    it('should parse numbers', () => {
        expect(parseExpressionIntoNode('2')).to.deep.equal(2);
        expect(parseExpressionIntoNode('2.33')).to.deep.equal(2.33);
        expect(parseExpressionIntoNode('-1')).to.deep.equal({
            operation: OperationsEnum.MULTIPLY,
            operand1: -1,
            operand2: '1',
        });
        expect(parseExpressionIntoNode('-1.22')).to.deep.equal({
            operation: OperationsEnum.MULTIPLY,
            operand1: -1,
            operand2: '1.22',
        });
    });
    it('should parse simple expressions', () => {
        expect(parseExpressionIntoNode('2+5')).to.deep.equal({
            operation: OperationsEnum.PLUS,
            operand1: '2',
            operand2: '5',
        });
        expect(parseExpressionIntoNode('2/5')).to.deep.equal({
            operation: OperationsEnum.DIVISION,
            operand1: '2',
            operand2: '5',
        });
    });
    it('should parse expressions with >2 operators', () => {
        expect(parseExpressionIntoNode('2+5*7')).to.deep.equal({
            operation: OperationsEnum.PLUS,
            operand1: '2',
            operand2: '5*7',
        });
        expect(parseExpressionIntoNode('2*5-7')).to.deep.equal({
            operation: OperationsEnum.MINUS,
            operand1: '2*5',
            operand2: '7',
        });
    });
    it('should calculate simple expressions', () => {
        expect(calculateMixedNodeResult('2+5')).to.equal(7);
        expect(calculateMixedNodeResult('2*5')).to.equal(10);
        expect(calculateMixedNodeResult('4/2')).to.equal(2);
        expect(calculateMixedNodeResult('2-7')).to.equal(-5);
        expect(calculateMixedNodeResult('2')).to.equal(2);
        expect(calculateMixedNodeResult('-1')).to.equal(-1);
    });
    it('should calculate brackets expressions', () => {
        expect(calculateMixedNodeResult('((80-(19)))')).to.equal(61);
    });
    it('should parse brackets expressions', () => {
        expect(parseExpressionIntoNode('1')).to.deep.equal(1);
        expect(parseExpressionIntoNode('(1+2)')).to.deep.equal({
            operation: OperationsEnum.PLUS,
            operand1: '1',
            operand2: '2',
        });
        expect(parseExpressionIntoNode('(2+4)*4+(1+(2+5))-1')).to.deep.equal({
            operation: OperationsEnum.PLUS,
            operand1: '(2+4)*4',
            operand2: '(1+(2+5))-1',
        });
    });
    it('parses complex expressions with minuses', () => {
        expect(parseExpressionIntoNode('12*-1')).to.deep.equal({
            operation: OperationsEnum.MULTIPLY,
            operand1: '12',
            operand2: '-1',
        });
    });
    it('parses complex expressions with brackets', () => {
        expect(calculateMixedNodeResult('(1-2)+-(-(-(-4)))')).to.equal(3);
    });
    it('calculated examples', () => {
        expect(calc('(123.45*(678.90 / (-2.5+ 11.5)-(((80 -(19))) *33.25)) / 20) - (123.45*(678.90 / (-2.5+ 11.5)-(((80 -(19))) *33.25)) / 20) + (13 - 2)/ -(-11) ')).to.equal(1);
    });
});

describe('brackets parsers', () => {
    it('should parse brackets expressions', () => {
        expect(getBracketsBlocks('(2+4)+4')).to.deep.equal([
            {
                bracket: '(2+4)',
                startIndex: 0,
                endIndex: 4,
            },
        ]);
        expect(getBracketsBlocks('(2+(4*2))+(4)')).to.deep.equal([
            {
                bracket: '(2+(4*2))',
                startIndex: 0,
                endIndex: 8,
            },
            {
                bracket: '(4)',
                startIndex: 10,
                endIndex: 12,
            },
        ]);
        expect(getBracketsBlocks('2+4+4')).to.deep.equal([]);
    });
});

var tests: [string, number][] = [
    ['1+1', 2],
    ['1 - 1', 0],
    ['1* 1', 1],
    ['1 /1', 1],
    ['-123', -123],
    ['123', 123],
    ['2 /2+3 * 4.75- -6', 21.25],
    ['12* 123', 1476],
    ['2 / (2 + 3) * 4.33 - -6', 7.732],
];

describe("calc", function() {
    it("should evaluate correctly", () => {
        tests.forEach(function(m) {
            var x = calc(m[0]);
            var y = m[1];
            expect(x).to.equal(y, 'Expected: "' + m[0] + '" to be ' + y + ' but got ' + x);
        });
    });
});
