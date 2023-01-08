export function lengthOfLongestSubstring(s: string): number {
    if (!s.length) {
        return 0;
    }

    let longestStringLength = 1;
    let currentString = s[0];
    let currentLetter = s[0];
    let letterIndexInLongestString = -1;

    for (let currentLetterIndex = 1; currentLetterIndex < s.length; currentLetterIndex++) {
        currentLetter = s[currentLetterIndex];
        letterIndexInLongestString = currentString.indexOf(currentLetter);

        if (letterIndexInLongestString === -1) {
            currentString += currentLetter;
            if (currentString.length > longestStringLength) {
                longestStringLength = currentString.length;
            }
        } else {
            currentString = currentString.substring(letterIndexInLongestString + 1) + currentLetter;
        }
    }

    return longestStringLength;
}
