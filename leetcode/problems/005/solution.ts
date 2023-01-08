export function longestPalindrome(s: string): string {
    switch (s.length) {
        case 0:
            return '';
        case 1:
            return s;
        case 2:
            return s[0] === s[1] ? s : s[0];
    }

    let longestPalindrome = s[0];

    const findLargestPalindromeFromIndices = function (palindromeStartIndex: number, palindromeEndIndex?: number): string {
        let palindrome = s[palindromeStartIndex];
        if (palindromeEndIndex !== undefined) {
            palindrome += s[palindromeEndIndex];
        } else {
            palindromeEndIndex = palindromeStartIndex;
        }

        let longestIntermediatePalindrome = palindrome;
        palindromeStartIndex -= 1;
        palindromeEndIndex += 1;

        while (palindromeStartIndex >= 0 && palindromeEndIndex < s.length) {
            let palindromeStartLetter = s[palindromeStartIndex];
            let palindromeEndLetter = s[palindromeEndIndex];
            if (palindromeStartLetter !== palindromeEndLetter) {
                break;
            }

            palindromeStartIndex -= 1;
            palindromeEndIndex += 1;
            palindrome = palindromeStartLetter + palindrome + palindromeEndLetter;
            if (palindrome.length > longestIntermediatePalindrome.length) {
                longestIntermediatePalindrome = palindrome;
            }
        }

        return longestIntermediatePalindrome;
    }

    for (let currentLetterIndex = 0; currentLetterIndex < s.length - 1; currentLetterIndex++) {
        let currentLetter = s[currentLetterIndex];
        let nextLetterIndex = currentLetterIndex + 1;
        let nextLetter = s[nextLetterIndex];

        const isDouble = currentLetter === nextLetter;

        if (isDouble) {
            const longestPalindromeFromIndices = findLargestPalindromeFromIndices(currentLetterIndex, nextLetterIndex);
            if (longestPalindromeFromIndices.length > longestPalindrome.length) {
                longestPalindrome = longestPalindromeFromIndices
            }
        }

        if (currentLetterIndex > 0) {
            const longestPalindromeFromIndices = findLargestPalindromeFromIndices(currentLetterIndex);
            if (longestPalindromeFromIndices.length > longestPalindrome.length) {
                longestPalindrome = longestPalindromeFromIndices
            }
        }
    }

    return longestPalindrome;
}
