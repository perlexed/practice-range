export function convert(s: string, numRows: number): string {
    if (numRows === 1) {
        return s;
    }

    const periodIncrement =  2 * numRows - 2;

    let resultString = '';
    let currentLetterIndex = 0;
    let currentLetter = '';

    for (let rowIndex = 0; rowIndex < numRows; rowIndex++) {
        for (let elemIndex = 0; elemIndex < s.length; elemIndex++) {
            currentLetterIndex = rowIndex + elemIndex * periodIncrement;
            currentLetter = s[currentLetterIndex];

            if (currentLetterIndex >= s.length) {
                break;
            }

            resultString += currentLetter;

            if (rowIndex > 0 && rowIndex < numRows - 1) {
                currentLetterIndex +=  2 * (numRows - (rowIndex + 1));
                currentLetter = s[currentLetterIndex];
                if (currentLetterIndex < s.length) {
                    resultString += currentLetter;
                }
            }
        }
    }

    return resultString;
}
