
enum InterestEnum {
    None,
    Near,
    Yes,
}

interface CheckerInterface {
    (num: number): boolean
}

const ZeroesChecker: CheckerInterface = (num: number): boolean => /^[1-9][0]{2,}$/.test(num.toString());
const SameDigitChecker: CheckerInterface = (num: number): boolean => {
    const numDigits = num.toString().split('');
    return numDigits.every(digit => digit === numDigits[0]);
};
const IncreasingSequenceChecker: CheckerInterface = num => '1234567890'.includes(num.toString());
const DecreasingSequenceChecker: CheckerInterface = num => '9876543210'.includes(num.toString());
const PalindromeChecker: CheckerInterface = num => {
    const numDigits = num.toString().split('');

    for (let digitIndex = 0; digitIndex < Math.floor(numDigits.length / 2); digitIndex++) {
        const leftDigit = numDigits[digitIndex];
        const rightDigit = numDigits[numDigits.length - (1 + digitIndex)];
        if (leftDigit !== rightDigit) {
            return false;
        }
    }
    return true;
};

const nearNumbersChecker = (checker: CheckerInterface, num: number): InterestEnum => {
    if (checker(num)) {
        return InterestEnum.Yes;
    }

    if (checker(num + 1) || checker(num + 2)) {
        return InterestEnum.Near;
    }

    return InterestEnum.None;
}

export function isInteresting(n: number, awesomePhrases: number[]): number {

    if (n < 98 || n > 1000000000) {
        return InterestEnum.None;
    }

    if (n === 98 || n === 99) {
        return InterestEnum.Near;
    }

    let currentInterestStatus = InterestEnum.None;
    let checkerResult = InterestEnum.None;

    const checkers: CheckerInterface[] = [
        (num: number): boolean => awesomePhrases.includes(num),
        ZeroesChecker,
        SameDigitChecker,
        IncreasingSequenceChecker,
        DecreasingSequenceChecker,
        PalindromeChecker,
    ];

    for (let checkerIndex = 0; checkerIndex < checkers.length; checkerIndex++) {
        checkerResult = nearNumbersChecker(checkers[checkerIndex], n);

        if (checkerResult === InterestEnum.Yes) {
            return checkerResult;
        }

        if (checkerResult > currentInterestStatus) {
            currentInterestStatus = checkerResult
        }
    }

    return currentInterestStatus;
}