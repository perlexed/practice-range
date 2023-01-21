export function myAtoi(s: string): number {
    const numberMatchRegexp = new RegExp('^[ ]*([\\+\-]?[\\d]+)')
    const numberMatchResult = numberMatchRegexp.exec(s);

    if (!numberMatchResult) {
        return 0;
    }

    const signedNumberString = numberMatchResult[1];

    if (!signedNumberString.length) {
        return 0;
    }

    const positiveSign = '+';
    const negativeSign = '-';
    let signMultiplier = 1;

    let zeroedUnsignedNumberString = signedNumberString;

    const firstChar = signedNumberString[0];
    if([positiveSign, negativeSign].includes(firstChar)) {
        if (firstChar === negativeSign) {
            signMultiplier = -1;
        }
        zeroedUnsignedNumberString = signedNumberString.substring(1);
    }

    const unsignedNumberString = zeroedUnsignedNumberString.replace(/^0*/g, '');

    if (!unsignedNumberString.length) {
        return 0;
    }

    const numberUpperLimit = 2147483647;
    const numberLowerLimit = -2147483648;

    if (unsignedNumberString.length > numberUpperLimit.toString().length) {
        return signMultiplier > 0
            ? numberUpperLimit
            : numberLowerLimit;
    }

    const targetNumber = parseInt(unsignedNumberString);

    if (signMultiplier > 0) {
        if (targetNumber > numberUpperLimit) {
            return numberUpperLimit;
        }
    } else {
        if (targetNumber * signMultiplier < numberLowerLimit) {
            return numberLowerLimit;
        }
    }

    return signMultiplier * targetNumber;
}
