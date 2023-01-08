
interface calculationState {
    seq: number[],
    previousAddend: number,
    remainingSum: number,
}

const findAddendSequence = function (state: calculationState): null|number[] {

    let currentAddend = state.previousAddend;

    while (currentAddend > 1) {
        currentAddend = currentAddend - 1;

        const diff = state.remainingSum - (currentAddend * currentAddend);

        if (diff < 0) {
            continue;
        }

        if (currentAddend === 1 && diff === 1) {
            return null;
        }

        const newState = {
            seq: [currentAddend, ...state.seq],
            remainingSum: diff,
            previousAddend: currentAddend,
        };

        if (diff == 0) {
            return newState.seq;
        }

        const checkResult = findAddendSequence(newState);

        if (checkResult !== null) {
            return checkResult;
        }
    }

    return null;
}

/**
 * @see https://www.codewars.com/kata/54eb33e5bc1a25440d000891/train/typescript
 */
export class G964 {
    public static decompose = (n: number) => {

        const state = {
            seq: [],
            previousAddend: n,
            remainingSum: n * n,
        };

        return findAddendSequence(state);
    }
}