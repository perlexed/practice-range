
interface IStorage {
    processed: number[],
    unprocessed: number[],
}

const crossRunsStorage = {
    processed: [],
    unprocessed: [1],
};

const numberSort = (a: number, b: number) => a - b;

const processNextSequence = function (sequenceStorage: IStorage): IStorage {
    const nextNumberToProcess = Math.min.apply( Math, sequenceStorage.unprocessed );
    const nextNumberIndex = sequenceStorage.unprocessed.indexOf(nextNumberToProcess);
    sequenceStorage.unprocessed.splice(nextNumberIndex, 1);

    if (nextNumberToProcess === undefined) {
        throw "unprocessed list cannot be empty";
    }

    [
        (2 * nextNumberToProcess) + 1,
        (3 * nextNumberToProcess) + 1,
    ].forEach((nextNumber: number) => {
        if (!sequenceStorage.unprocessed.includes(nextNumber)) {
            sequenceStorage.unprocessed.push(nextNumber);
        }
    });

    sequenceStorage.processed.push(nextNumberToProcess);

    return sequenceStorage;
};

const getLinearPositionAt = function (storage: IStorage, position: number): number {
    for (let i = 0; i < position; i++) {
        processNextSequence(storage);
    }

    const unprocessedSlice = storage.unprocessed.slice(0, position - (storage.processed.length + 1));

    unprocessedSlice.sort(numberSort);
    console.log([...storage.processed, ...unprocessedSlice]);
    return [...storage.processed, ...unprocessedSlice][position];
}

export function dblLinear(n: number): number {
    return getLinearPositionAt(crossRunsStorage, n);
}