/**
 * @see https://www.codewars.com/kata/52a78825cdfc2cfc87000005
 */

export enum OperationsEnum {
    PLUS = '+',
    MINUS = '-',
    MULTIPLY = '*',
    DIVISION = '/',
}

const calculateOperationResult = (operation: OperationsEnum, operand1: number, operand2: number): number => {
    switch (operation) {
        case OperationsEnum.PLUS:
            return operand1 + operand2;
        case OperationsEnum.MINUS:
            return operand1 - operand2;
        case OperationsEnum.MULTIPLY:
            return operand1 * operand2;
        case OperationsEnum.DIVISION:
            return operand1 / operand2;
        default:
            throw new Error(`Unsupported operation ${operation}`);
    }
}

const operatorsRegexByPrecedence = [
    {
        regexp: new RegExp('\\' + OperationsEnum.MULTIPLY + OperationsEnum.MINUS),
        operation: OperationsEnum.MULTIPLY,
    },
    {
        regexp: new RegExp('\\' + OperationsEnum.DIVISION + OperationsEnum.MINUS),
        operation: OperationsEnum.DIVISION,
    },
    {
        regexp: new RegExp('\\' + OperationsEnum.PLUS),
        operation: OperationsEnum.PLUS,
    },
    {
        regexp: new RegExp('\\' + OperationsEnum.MINUS),
        operation: OperationsEnum.MINUS,
    },
    {
        regexp: new RegExp('\\' + OperationsEnum.MULTIPLY),
        operation: OperationsEnum.MULTIPLY,
    },
    {
        regexp: new RegExp('\\' + OperationsEnum.DIVISION),
        operation: OperationsEnum.DIVISION,
    },
];

interface OperationNode {
    operation: OperationsEnum,
    operand1: MixedNode,
    operand2: MixedNode,
}

type MixedNode = string|number|OperationNode

export const calculateMixedNodeResult = (node: MixedNode): number => {
    if (typeof node === 'number') {
        return node;
    }

    if (typeof node === 'string') {
        const nodeParseResult = parseExpressionIntoNode(node);

        if (typeof nodeParseResult === 'number') {
            return nodeParseResult;
        }

        return calculateNodeResult(nodeParseResult);
    }

    return calculateNodeResult(node);
}

const calculateNodeResult = (node: OperationNode): number => {
    const getOperandNumberValue = (operand: MixedNode): number => typeof operand === 'number'
        ? operand
        : calculateMixedNodeResult(operand);

    return calculateOperationResult(
        node.operation,
        getOperandNumberValue(node.operand1),
        getOperandNumberValue(node.operand2),
    );
};

export const parseExpressionIntoNode = (expression: string): OperationNode|number =>{
    expression = clearSurroundingBrackets(expression);

    const bracketsBlocksReplacements: {bracketsString: string, placeholder: string}[] = [];

    getBracketsBlocks(expression).forEach((bracketsBlock, index) => {
        /**
         * @example PLACEHOLDER3
         */
        const placeholder = '_PLACEHOLDER' + index + '_';
        expression = expression.replace(bracketsBlock, placeholder);

        bracketsBlocksReplacements.push({
            bracketsString: bracketsBlock,
            placeholder: placeholder,
        });
    });

    let foundOperator = null;
    let foundOperatorIndex = null;

    for (
        let operatorIndex = 0;
        operatorIndex < operatorsRegexByPrecedence.length && foundOperatorIndex === null;
        operatorIndex++
    ) {
        const operatorInfo = operatorsRegexByPrecedence[operatorIndex];
        const operatorSearchResult = operatorInfo.regexp.exec(expression);

        if (operatorSearchResult !== null) {
            foundOperator = operatorInfo.operation;
            foundOperatorIndex = operatorSearchResult.index;
        }
    }

    if (foundOperatorIndex === null || foundOperator === null) {
        return parseFloat(expression);
    }

    let operand1: string|OperationNode = expression.substr(0, foundOperatorIndex);
    const operand2 = expression.substr(foundOperatorIndex + 1);

    const returnBracketsBlocks = (expressionWithPlaceholders: string): string => {
        return bracketsBlocksReplacements.reduce(
            (expr, {bracketsString, placeholder}): string => expr.replace(placeholder, bracketsString),
            expressionWithPlaceholders
        );
    };

    if (operand1 === '' && foundOperator === OperationsEnum.MINUS) {
        return {
            operation: OperationsEnum.MULTIPLY,
            operand1: -1,
            operand2: returnBracketsBlocks(operand2),
        };
    }

    return {
        operation: foundOperator,
        operand1: returnBracketsBlocks(operand1),
        operand2: returnBracketsBlocks(operand2),
    };
}

const clearSurroundingBrackets = (expression: string): string => {
    const isSurroundedByBrackets = (stringExpression: string) => {
        const bracketsBlocks = getBracketsBlocks(stringExpression);
        return bracketsBlocks.length && bracketsBlocks[0] === stringExpression;
    };

    while (isSurroundedByBrackets(expression)) {
        expression = expression.substr(1, expression.length - 2);
    }

    return expression;
};

export function getBracketsBlocks(expression: string): string[]
{
    const bracketsBlocks: string[] = [];
    let bracketsCount = 0;
    let currentBracketStart = -1;

    expression.split('').forEach((expressionLetter: string, letterIndex) => {
        switch (expressionLetter) {
            case '(':
                if (bracketsCount === 0) {
                    currentBracketStart = letterIndex;
                }
                bracketsCount++;
                return;
            case ')':
                bracketsCount--;

                if (bracketsCount === 0) {
                    bracketsBlocks.push(
                        expression.substr(currentBracketStart, letterIndex - currentBracketStart + 1)
                    );
                }
                return;
        }
    });

    return bracketsBlocks;
}

export function calc(expression: string): number {
    return calculateMixedNodeResult(expression.split(' ').join(''));
}
