export function reverse(x: number): number {
    const minusSign = '-';
    const maxIntPart = 147483647;
    let numberAsString = x.toString();
    const isNegative = numberAsString[0] === minusSign;

    if (isNegative) {
        numberAsString = numberAsString.substring(1);
    }

    let reversedNumberAsString = '';
    let currentDigit = '';

    for (let numberIndex = 0; numberIndex < numberAsString.length; numberIndex++) {
        currentDigit = numberAsString[numberIndex];

        // Checking on reaching 2^31 - 1 limit
        if (numberIndex === 9) {
            switch (currentDigit) {
                case '0':
                case '1':
                    break;
                case '2':
                    if (parseInt(reversedNumberAsString) > maxIntPart) {
                        return 0;
                    }
                    break;
                default:
                    return 0;
            }
        }

        reversedNumberAsString = currentDigit + reversedNumberAsString;
    }

    if (isNegative) {
        reversedNumberAsString = minusSign + reversedNumberAsString;
    }

    return parseInt(reversedNumberAsString);
}
