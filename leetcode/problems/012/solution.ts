export function intToRoman(num: number): string {
    const orderDigits = num
        .toString()
        .split('')
        .map(digitString => parseInt(digitString))
        .reverse();

    const romanBuilderLetters = [
        {one: 'I', five: 'V'},
        {one: 'X', five: 'L'},
        {one: 'C', five: 'D'},
        {one: 'M', five: ''},
    ];

    const resultOrderStrings = orderDigits.map((orderDigit, orderDigitIndex) => {
        const romanLettersPair = romanBuilderLetters[orderDigitIndex];

        if (orderDigit < 4) {
            return romanLettersPair.one.repeat(orderDigit);
        }
        if (orderDigit === 4) {
            return romanLettersPair.one + romanLettersPair.five;
        }
        if (orderDigit < 9) {
            return romanLettersPair.five + romanLettersPair.one.repeat(orderDigit - 5);
        }

        return romanLettersPair.one + romanBuilderLetters[orderDigitIndex + 1].one;
    });

    return resultOrderStrings.reverse().join('');
}
