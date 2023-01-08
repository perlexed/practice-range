
interface ILetterCount { [key: string]: number }
interface ILetterInfo {letter: string, count: number, topGroup: number}

export class G964 {
    public static mix = (s1: string, s2: string): string => {
        const s1LettersCount = G964.getLettersCount(s1);
        const s2LettersCount = G964.getLettersCount(s2);

        return 'abcdefghijklmnopqrstuvwxyz'
            .split('')
            .reduce((acc: ILetterInfo[], letter: string) => {
                const s1LetterCount = s1LettersCount[letter] ?? 0;
                const s2LetterCount = s2LettersCount[letter] ?? 0;

                if (s1LetterCount === s2LetterCount) {
                    acc.push({
                        letter: letter,
                        topGroup: 3,
                        count: s1LetterCount,
                    });
                } else {
                    const topGroup = s1LetterCount > s2LetterCount ? 1 : 2;
                    acc.push({
                        letter: letter,
                        topGroup: topGroup,
                        count: s1LetterCount > s2LetterCount ? s1LetterCount : s2LetterCount,
                    })
                }

                return acc;
            }, [])
            .filter(letterInfo => letterInfo.count > 1)
            .sort((letter1Info, letter2Info) => {
                if (letter1Info.count !== letter2Info.count) {
                    return letter1Info.count < letter2Info.count ? 1 : -1;
                }

                if (letter1Info.topGroup !== letter2Info.topGroup) {
                    return letter1Info.topGroup - letter2Info.topGroup;
                }

                if (letter1Info.letter === letter2Info.letter) {
                    return 0;
                }
                return letter1Info.letter < letter2Info.letter ? -1 : 1;
            })
            .map(letterInfo => {
                return (letterInfo.topGroup === 3 ? '=' : letterInfo.topGroup)
                    + ':'
                    + letterInfo.letter.repeat(letterInfo.count);
            })
            .join('/');
    }

    private static getLettersCount(str: string): ILetterCount {
        return str
            .replace(/([^a-z])/g, '')
            .split('')
            .reduce((acc: ILetterCount, char: string): object => ({
                ...acc,
                [char]: acc[char] ? acc[char] + 1 : 1,
            }), {});
    }
}